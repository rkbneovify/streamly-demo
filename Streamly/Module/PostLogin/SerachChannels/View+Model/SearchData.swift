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
                print(contactModel)
            }
            print("Success")
        }) { (error, data, code)  in

            print("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
}

class SearchDataParser {

    static func parseContactData(_ data: Data) -> ChannelResponse? {
        do {
            let contactModelElement = try JSONDecoder().decode(ChannelResponse.self, from: data)


            return contactModelElement
        } catch {
            print(error)
        }
        return nil
    }
}


