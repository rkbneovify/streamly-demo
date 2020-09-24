//
//  SearchData.swift
//  Streamly
//
//  Created by Rohit Singh on 6/15/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation


class SearchDataViewModel: NSObject {

    var searchData:((ChannelResponse)->())?
    weak var delegate:HandleError?

    func getSearchData() {
        ApiCall.getSearchData(success: { data
            in
            if let contactModel = SearchDataParser.parseContactData(data!) {
                self.searchData!((contactModel))
                debugPrint(contactModel)
            }
            debugPrint("Success")
        }) { (error, data, code)  in

            debugPrint("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "")
            if code == 401 {
                Constant.logOut()
            }
        }
    }
}

class SearchDataParser {

    static func parseContactData(_ data: Data) -> ChannelResponse? {
        do {
            let contactModelElement = try JSONDecoder().decode(ChannelResponse.self, from: data)


            return contactModelElement
        } catch {
            debugPrint(error)
        }
        return nil
    }
}


