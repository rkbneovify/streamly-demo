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

    func getSearchData(search: String = "") {
        ApiCall.getSearchData(search: search,success: { data, _
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


            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
}


