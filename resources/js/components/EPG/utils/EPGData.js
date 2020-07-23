/**
 * Created by satadru on 3/30/17.
 */


import MockDataService from '../utils/MockDataService';

export default class EPGData {
    // constructor(){
    //      (async ()=> {
    //         this.channels= await MockDataService.getMockDataRaw();
    //         this.channels.forEach((values, key) => {
                
    //             values.events.forEach(v => {v.isCurrent = Date.now()>= v.start && Date.now() <= v.end;});

    //         });

    //       this.len = this.channels.length;
          
    //     })();
        
    // }
    constructor(){
        this.channels= MockDataService.getMockDataRaw();
        this.retrievedObject = localStorage.getItem('mychanneldata');

        this.channels= JSON.parse(this.retrievedObject);
        this.channels.forEach((values, key) => {
                
                values.events.forEach(v => {v.isCurrent = Date.now()>= v.start && Date.now() <= v.end;});

            });
    }
   
    getChannelCount() {
        return this.channels.length;
    }
    getChannel(position) {
        return this.channels[position];
    }

    getEvents(channelPosition) {
        let channel = this.channels[channelPosition];
        let events = channel.events;
        return events
    }

    getEventCount(channelPosition) {
        return this.getEvents(channelPosition).length;
    }

    getEvent(channelPosition, programPosition) {
        let channel = this.channels[channelPosition];
        let events = channel.events;
        return events[programPosition];
    }

    getEventPosition(channelPosition, event) {
        let events = this.channels[channelPosition].events;
        for (let i = 0; i < events.length; i++) {
            if (this.isEventSame(event, events[i])) {
                return i;
            }
        }
    }

   

    isEventSame(event1, event2) {
        if (event1.start == event2.start && event1.end == event2.end) {
            return true;
        }
        return false;
    }

    hasData() {
        return this.getChannelCount() > 0;
    }
}
