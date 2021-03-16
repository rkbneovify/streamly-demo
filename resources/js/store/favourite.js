/*eslint-disable*/
import * as TYPES from './mutation-types'
import _ from 'lodash'
import { PATH, API_ENDPOINTS, API_DATE_FORMAT } from './config'

import axios from 'axios';
// State
const state = {
  favourites: {},
  fetching: false,
  fetched: false
}

// Getters
const getters = {
  favourites: state => state.favourites,
  isFavouritesFetching: state => state.fetching,
  isFavouritesFetched: state => state.fetched
}

// Mutations
const mutations = {
  [TYPES.FETCHING_FAVORITE_LIST](state) {
    state.fetching = true
    state.fetched = false
  },
  [TYPES.FETCHING_FAVORITE_LIST_SUCCESS](state, favourites) {
    state.fetching = false
    state.fetched = true
    state.favourites = favourites
  },
  [TYPES.FETCHING_FAVORITE_LIST_ERROR](state, errorMessage) {
    state.fetching = false
    state.fetched = false
    state.errorMessage = errorMessage
  },
  [TYPES.MODIFY_FAVOURITES](state, favourites) {
    state.favourites = favourites
  },
  [TYPES.CLEAR_FAVOURITES](state) {
    state.favourites = {}
  }
}

// Actions
const actions = {
  getFavouriteList({ state }) {
    let favourites = [];
     console.log('statefav',state.favourites);
    for (let i in state.favourites) {
      // favourites.push(state.favourites[i])
      favourites.push(state.favourites[i].channelId)
    }
    return favourites
  },
  clearFavouriteList({ commit }) {
    commit(TYPES.MODIFY_FAVOURITES)
  },
  toggleFavourites({ state, commit }, { channel }) {
    var model = {
      CallSign:null,
      channelId:null,
      channelStbNumber:null,
      channelTitle:null
   };
   var result = _.pick(channel, _.keys(model));

// console.log('togglechannel',channel)
// console.log('togglechannelresult',result)
    let favourites = Object.assign({}, state.favourites) // Array copy  
    let channelId = result.channelId

    if (favourites[channelId]) {
      delete favourites[channelId]
    } else {
      favourites[channelId] = result
    }
    commit(TYPES.MODIFY_FAVOURITES, favourites)
  },
   /**
   * Fetches All Channels
   */
  fetchingFavoriteList({ commit }) { commit(TYPES.FETCHING_FAVORITE_LIST) },
    /**
   * Fetches All the channels
   * @param {object} store - vuex store
   */
  async fetchFavoriteList({ state, commit, getters, dispatch }) {

    commit(TYPES.FETCHING_FAVORITE_LIST)
    let response = await axios.get(`${PATH}${API_ENDPOINTS.FAVORITE_LIST}`);
    // console.log('fetchChannelList',response);
    // Delay for showing loading screen
    // setTimeout(function() {
    // let response = await fetch(['./static/channels.json']);
    let data = await response.data;
    if (data.responseCode != "200") {
      commit(TYPES.FETCHING_FAVORITE_LIST_ERROR, data.responseMessage)
    } else {
     let arr= _.keyBy(data.favorites);

  let  a = _.map(arr, function(o){
   

    return {channelId: o,
    }
 
 });
//  console.log('achanged',a);
 let myarr=_.keyBy(a, 'channelId');
 console.log('myarrwithchannelid',myarr);
commit(TYPES.FETCHING_FAVORITE_LIST_SUCCESS, myarr);
      // console.log('fav',data.favorites)
      // Fetching Programme data
      // dispatch('fetchProgrammeTimingList')
    }
    // }, 2000)
  },
}

export default {
  state,
  getters,
  actions,
  mutations
}
