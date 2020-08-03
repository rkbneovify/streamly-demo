//
//  ChannelList+Program.swift
//  Streamly
//
//  Created by Rohit Singh on 6/23/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

class EPGViewModel: NSObject {

    var epgData:((EPGResponse)->())?
    var recentData:((RecentData)->())?
    var channelData:((ChannelListResponse)->())?
    weak var delegate:HandleError?

    func getEPGData() {
        ApiCall.getEPGData(success: { data
            in
            if let epgModel = EPGDataParser.parseContactData(data!) {
                self.epgData!((epgModel))
                //debugPrint(epgModel)
            }
            debugPrint("Success")
        }) { (error, data, code)  in

            debugPrint("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }

    func getChannelList() {
        ApiCall.getChannelList(success: { data
            in
            if let epgModel = EPGDataParser.parseChannelData(data!) {
                self.channelData!((epgModel))
                //debugPrint(epgModel)
            }
            debugPrint("Success")
        }) { (error, data, code)  in

            debugPrint("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }

    func getRecentData() {
        ApiCall.getRecentData(success: { data
            in
            if let recentModel = EPGDataParser.parserecentData(data!) {
                self.recentData!((recentModel))
                //debugPrint(epgModel)
            }
            debugPrint("Success")
        }) { (error, data, code)  in

            debugPrint("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }

    func postRecentData(_ ids:String) {
        ApiCall.postRecentData(ids,success: { data
            in
            if let recentModel = EPGDataParser.parserecentData(data!) {
                self.recentData!((recentModel))
                //debugPrint(epgModel)
            }
            debugPrint("Success")
        }) { (error, data, code)  in

            debugPrint("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
}

class EPGDataParser {

    static func parseContactData(_ data: Data) -> EPGResponse? {
        do {
            let contactModelElement = try JSONDecoder().decode(EPGResponse.self, from: data)


            return contactModelElement
        } catch {
            debugPrint(error)
        }
        return nil
    }

    static func parseChannelData(_ data: Data) -> ChannelListResponse? {
           do {

               let contactModelElement = try JSONDecoder().decode(ChannelListResponse.self, from: data)


               return contactModelElement
           } catch {
               debugPrint(error)
           }
           return nil
       }

    static func parserecentData(_ data: Data) -> RecentData? {
        do {

            let contactModelElement = try JSONDecoder().decode(RecentData.self, from: data)


            return contactModelElement
        } catch {
            debugPrint(error)
        }
        return nil
    }
}
