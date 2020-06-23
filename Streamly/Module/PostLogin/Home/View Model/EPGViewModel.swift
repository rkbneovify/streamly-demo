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
    weak var delegate:HandleError?

    func getEPGData() {
        ApiCall.getEPGData(success: { data
            in
            if let epgModel = EPGDataParser.parseContactData(data!) {
                self.epgData!((epgModel))
                print(epgModel)
            }
            print("Success")
        }) { (error, data, code)  in

            print("failure")
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
            print(error)
        }
        return nil
    }
}
