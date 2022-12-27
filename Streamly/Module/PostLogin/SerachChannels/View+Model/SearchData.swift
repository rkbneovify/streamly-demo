//
//  SearchData.swift
//  Streamly
//
//  Created by Rohit Singh on 6/15/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation


class SearchDataViewModel: NSObject {

    var searchData:((ChannelData)->())?
    weak var delegate:HandleError?

    func getAllChannelListForSearch(search: String = "") {
        ApiCall.getAllChannelList(success: { data, _
            in
            if let contactModel = SearchDataParser.parseContactData(data!) {
                self.searchData?((contactModel))
                //debugPrint(contactModel)
            }
            //debugPrint("Success")
        }) { (error, data, code)  in

            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                self.delegate?.handleError(error?.localizedDescription ?? "", failedData.response_code ?? 0)
                return
            }
            //debugPrint("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
        }
    }
}

class SearchDataParser {

    static func parseContactData(_ data: Data) -> ChannelData? {
        do {
            let contactModelElement = try JSONDecoder().decode(ChannelData.self, from: data)


            print("hfhfhfhfhkgkhkhkhk")
            return contactModelElement
        } catch {
            print("65655565")
            //debugPrint(error)
        }
        return nil
    }
}


