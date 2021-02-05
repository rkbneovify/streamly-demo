/*eslint-disable*/
import * as TYPES from './mutation-types'
import { PATH, API_ENDPOINTS, API_DATE_FORMAT } from './config'
import _ from 'lodash'
import moment from 'moment'
import axios from 'axios';


// Utility 
function getNearestHourDateTime(dateTimeStr) {
  return dateTimeStr ? moment(dateTimeStr).startOf('hour').format(API_DATE_FORMAT) : moment().startOf('hour').format(API_DATE_FORMAT)
}

function getMidnightDateTime(dateTimeStr) {
  return moment(dateTimeStr).endOf('day').format(API_DATE_FORMAT)
}

function getChannelWithNoProgrammes(sortedList) {

  let list = sortedList
    .slice(0, 20)
    .filter(channel => typeof channel.programmes == "undefined")
    .map(channel => channel.channelId)
    .join(',')

  return list
}

function setProgrammeRunTimeinMinutes(programmeList) {

  programmeList.forEach(programme => {
    let durationStr = programme.displayDuration.split(':');
    let minutes = Number(durationStr[0]) * 60 + Number(durationStr[1])

    programme['durationMinutes'] = minutes
  })
}

function sortProgrammeListByDateTime(programmeList) {

  return programmeList.sort((a, b) => {
    let momentA = moment(a.displayDateTime)
    let momentB = moment(b.displayDateTime)

    return moment(momentA).diff(momentB)
  })
}

// Initial State
const state = {
  channelList: [],
  sortOrder: -1,
  sortOn: 'channelTitle',
  fetching: false,
  fetched: false,

  timingFetching: false,
  timingFetched: false,

  programmeDate: getNearestHourDateTime(),

  errorMessage: ''
}

// getters
const getters = {
  channelList: state => state.channelList,
  isChannelListFetching: state => state.fetching,
  isChannelListFetched: state => state.fetched,
  sortOrder: state => state.sortOrder,
  sortOn: state => state.sortOn,

  programmeDate: state => state.programmeDate
}

// Mutations
const mutations = {

  [TYPES.FETCHING_CHANNEL_LIST](state) {
    state.fetching = true
    state.fetched = false
  },
  [TYPES.FETCHING_CHANNEL_LIST_SUCCESS](state, channelList) {
    state.fetching = false
    state.fetched = true
    state.channelList = channelList
  },
  [TYPES.FETCHING_CHANNEL_LIST_ERROR](state, errorMessage) {
    state.fetching = false
    state.fetched = false
    state.errorMessage = errorMessage
  },
  [TYPES.SAVE_SORTED_CHANNEL_LIST](state, { channelList, sortOn, sortOrder }) {
    state.channelList = channelList
    state.sortOn = sortOn
    state.sortOrder = sortOrder
  },

  [TYPES.FETCHING_CHANNEL_LIST_PROGRAMME](state) {
    state.timingFetching = true
    state.timingFetched = false
  },
  [TYPES.FETCHING_CHANNEL_LIST_PROGRAMME_SUCCESS](state, channelList) {
    state.timingFetching = false
    state.timingFetched = true
    state.channelList = channelList
  },
  [TYPES.FETCHING_CHANNEL_LIST_PROGRAMME_ERROR](state, errorMessage) {
    state.timingFetching = false
    state.timingFetched = false
    state.errorMessage = errorMessage
  },

  [TYPES.SET_PROGRAMME_DATE](state, programmeDate) {
    state.programmeDate = programmeDate
  }

}

// Actions
const actions = {

  /**
   * Fetches All Channels
   */
  fetchingChannelList({ commit }) { commit(TYPES.FETCHING_CHANNEL_LIST) },

  /**
   * Returns Array of all Channel Id's
   * @param {store} vuex store
   * @return {Object} promise
   */
  fetchChannelIdList({ state }) {

    let channelIdList = state.channelList.map(channel => channel.channelId)
    return channelIdList
  },

  /**
   * Cleans the Programme response and stores it in the Vuex Store
   * @param {object} store - vuex store
   * @param {array} programmes - Raw Programe Details response
   */
  setProgrammeTimingList({ state, commit, getters, dispatch }, programmes) {

    // 1.Group the raw programmes response according to the channel they belong
    let groupedProgrammeList = _.chain(programmes)
      .groupBy('channelId')
      .toPairs()
      .value().reduce((prev, val) => {
        prev[val[0]] = val[1]
        return prev
      }, {})

    // 2.Iterate over the Grouped List and add it to the respective Channels data in the Main ChannelList Vuex Store
    let channelList = [].concat(state.channelList)
    Object.keys(groupedProgrammeList).forEach(key => {

      let channelID = key;
      let channelData = channelList.find(channel => channel.channelId == key)

      let programmes = groupedProgrammeList[channelID] && groupedProgrammeList[channelID].length == 0 ? [{ programmeTitle: "NO DATA" }] : groupedProgrammeList[channelID]

      setProgrammeRunTimeinMinutes(programmes)
      channelData['programmes'] = programmes // setProgrammeRunTimeinMinutes(programmes)
    })

    commit(TYPES.FETCHING_CHANNEL_LIST_PROGRAMME_SUCCESS, channelList)
  },

  async fetchProgrammeTimings({ state, commit, getters, dispatch }, channelList) {
    // debugger
    let channelIDListWithNoProgramme = channelList.filter(id => {
      let channelData = state.channelList.find(channel => channel.channelId == id)
      return typeof channelData['programmes'] == "undefined"
    }).join(',')

    let api = `${PATH}${API_ENDPOINTS.CHANNEL_TV_GUIDE}?channelId=${channelList}&periodStart=${getters.programmeDate}&periodEnd=${getMidnightDateTime(getters.programmeDate)}`
    let response = await axios.get(api);
  
    let data = await response.data;

    if (data.responseCode != "200") {
      commit(TYPES.FETCHING_CHANNEL_LIST_PROGRAMME_ERROR, data.responseMessage)
    } else {
      // Set Programme Data onto the main Channel List Store
      dispatch('setProgrammeTimingList', data.getevent)
    }

  },

  /**
   * Returns Array of all Channel Id's
   * @param {object} store - vuex store
   */
  async fetchProgrammeTimingList({ state, commit, getters, dispatch }) {

    commit(TYPES.FETCHING_CHANNEL_LIST_PROGRAMME)

    let channelIdList = await dispatch('fetchChannelIdList')
    let channelList = getChannelWithNoProgrammes(state.channelList)

    // If List do not have any data needed to be fetched then commit 'FETCHING_CHANNEL_LIST_PROGRAMME_SUCCESS'
    if (!channelList) {
      commit(TYPES.FETCHING_CHANNEL_LIST_PROGRAMME_SUCCESS, [].concat(state.channelList))
      return;
    }

    let api = `${PATH}${API_ENDPOINTS.CHANNEL_TV_GUIDE}?channelId=${channelList}&periodStart=${getters.programmeDate}&periodEnd=${getMidnightDateTime(getters.programmeDate)}`
    let response = await axios.get(api);
    let data = await response.data;

    if (data.responseCode != "200") {
      commit(TYPES.FETCHING_CHANNEL_LIST_PROGRAMME_ERROR, data.responseMessage)
    } else {
      // Set Programme Data onto the main Channel List Store
      dispatch('setProgrammeTimingList', data.getevent)
    }
  },

  /**
   * Fetches All the channels
   * @param {object} store - vuex store
   */
  async fetchChannelList({ state, commit, getters, dispatch }) {

    commit(TYPES.FETCHING_CHANNEL_LIST)
    let response = await axios.get(`${PATH}${API_ENDPOINTS.CHANNEL_LIST}`);
    console.log('fetchChannelList',response);
    // Delay for showing loading screen
    // setTimeout(function() {
    // let response = await fetch(['./static/channels.json']);
    let data = await response.data;
    if (data.responseCode != "200") {
      commit(TYPES.FETCHING_CHANNEL_LIST_ERROR, data.responseMessage)
    } else {
      commit(TYPES.FETCHING_CHANNEL_LIST_SUCCESS, data.channels)
      // Fetching Programme data
      // dispatch('fetchProgrammeTimingList')
    }
    // }, 2000)
  },

  /**
   * Fetches All the channels
   * @param {object} store - vuex store
   * @param {string} sortOn - parameter to sort on
   * @param {number} sortOrder-  sort order
   */
  sortChannelList({ state, commit, dispatch }, { sortOn, sortOrder }) {
    let channelList = [].concat(state.channelList)

    let sortedChannelList = channelList.sort((a, b) => {
      let channelA = typeof a[sortOn] == "number" ? a[sortOn] : a[sortOn].toLowerCase().trim()
      let channelB = typeof b[sortOn] == "number" ? b[sortOn] : b[sortOn].toLowerCase().trim()
      return (channelA > channelB) ? sortOrder : (channelA < channelB) ? -sortOrder : 0
    })

    commit({
      type: TYPES.SAVE_SORTED_CHANNEL_LIST,
      channelList: sortedChannelList,
      sortOn,
      sortOrder
    })

    dispatch('fetchProgrammeTimingList')

  },
  /**
   * Fetches All the channels
   * @param {string} date - programme date
   * @param {number} sortOrder-  sort order
   */
  setProgrammeDate({ state, commit, dispatch }, date) {
    date = getNearestHourDateTime(date)

    commit(TYPES.SET_PROGRAMME_DATE, date)
  }

}

export default {
  state,
  getters,
  actions,
  mutations
}
