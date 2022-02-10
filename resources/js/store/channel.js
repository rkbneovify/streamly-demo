/*eslint-disable*/

import * as TYPES from './mutation-types'
import { PATH, API_ENDPOINTS } from './config'

import axios from 'axios';


// Initial State
const state = {
  channelDetails: null,
  channelDetailsList: null,
  fetching: false,
  fetched: false,
}

// // getters
const getters = {
  channelDetails: state => state.channelDetails,
  ischannelDetailsFetching: state => state.fetching,
  ischannelDetailsFetched: state => state.fetched
}

// // Mutations
const mutations = {

  [TYPES.FETCHING_CHANNEL_DETAILS](state) {
    state.fetching = true
    state.fetched = false
  },
  [TYPES.FETCHING_CHANNEL_DETAILS_SUCCESS](state, channelDetails) {
    state.fetching = false
    state.fetched = true
    state.channelDetails = channelDetails
  },
  [TYPES.FETCHING_CHANNEL_DETAILS_ERROR](state, errorMessage) {
    state.fetching = false
    state.fetched = false
    state.errorMessage = errorMessage
  },
  [TYPES.CLEAR_CHANNEL_DETAILS](state) {
    state.fetching = false
    state.fetched = false
    state.channelDetails = null
  },
  [TYPES.CACHE_CHANNEL_DETAILS](state, channelDetailsList) {
    state.channelDetailsList = channelDetailsList
  }
}

// Actions
const actions = {

  checkChannelDetailsPresent({ state }, channel) {

    let channelId = channel.channelId
    return Boolean(state.channelDetailsList && state.channelDetailsList[channelId] || false)
  },
  cacheChannelDetails({ state, commit, getters }, channelDetails) {

    let channelDetailsList = Object.assign({}, state.channelDetailsList) // Array copy  
    let channelId = channelDetails.channelId

    channelDetailsList[channelId] = channelDetails
    commit(TYPES.CACHE_CHANNEL_DETAILS, channelDetailsList)
  },

  async fetchChannelDetails({ state, commit, dispatch }, { channel }) {

    let channelId = channel.channelId
    commit(TYPES.FETCHING_CHANNEL_DETAILS)

    // 1. check cache 
    if (state.channelDetailsList && state.channelDetailsList[channelId]) {
      commit(TYPES.FETCHING_CHANNEL_DETAILS_SUCCESS, state.channelDetailsList[channelId])
    } else {

      //2. Fetch
      let response = await axios.get(`${PATH}${API_ENDPOINTS.CHANNEL_DETAILS}?channelId=${channelId}`);
      let data = await response.data;
      console.log('response',response)
      if (data.responseCode != "200") {
        commit(TYPES.FETCHING_CHANNEL_DETAILS_ERROR, data.responseMessage)
      } else {
        let channelDetails = data.channel[0]
        commit(TYPES.FETCHING_CHANNEL_DETAILS_SUCCESS, channelDetails)
        // Also caching for future use 
        dispatch('cacheChannelDetails', channelDetails)
      }
    }
  },
  async fetchChannelLogo({ state }) {
    let channelDetails = state.channelDetails

    // debugger
    // let imageData = state.channelDetails.channelExtRef.find(item => {
    //   return item.system.trim() == "Logo" && item.subSystem.trim() == "Pos_600x370"
    // })

   let checkDet= Boolean(state.channelDetails);
  //  console.log('checkDet',checkDet);
  //   console.log('channelDetails',state);
  if(checkDet){
    let imageData = state.channelDetails.icon;
    return imageData;}
  },
  clearChannelDetails({ commit }) {
    commit(TYPES.CLEAR_CHANNEL_DETAILS)
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}
