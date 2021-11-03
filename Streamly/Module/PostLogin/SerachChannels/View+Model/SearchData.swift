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
        ApiCall.getSearchData(success: { data, _
            in
            if let contactModel = SearchDataParser.parseContactData(data!) {
                self.searchData!((contactModel))
                //debugPrint(contactModel)
            }
            //debugPrint("Success")
        }) { (error, data, code)  in

            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) , failedData.response_code == 401 {
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
                return
            }
            //debugPrint("failure")
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
            //debugPrint(error)
        }
        return nil
    }
}


