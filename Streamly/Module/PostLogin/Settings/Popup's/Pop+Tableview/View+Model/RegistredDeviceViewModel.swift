//
//  RegistredDeviceViewModel.swift
//  Streamly
//
//  Created by Rohit Singh on 6/5/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

class RegistredDeviceViewModel: NSObject {

    var registredDevice:((DeviceResponseData)->())?
    weak var delegate:HandleError?

    func getRegistredDeviceData() {
        ApiCall.getRegistredDeviceData(success: { data
            in
            if let contactModel = DataParser.parseContactData(data!) {
                self.registredDevice!((contactModel))
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

class DataParser {

    static func parseContactData(_ data: Data) -> DeviceResponseData? {
        do {
            let contactModelElement = try JSONDecoder().decode(DeviceResponseData.self, from: data)


            return contactModelElement
        } catch {
            debugPrint(error)
        }
        return nil
    }
}


