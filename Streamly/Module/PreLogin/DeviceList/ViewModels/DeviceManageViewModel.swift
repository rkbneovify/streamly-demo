//
//  DeviceManageViewModel.swift
//  Streamly
//
//  Created by Aditya Joshi on 26/07/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import UIKit
import Foundation

class DeviceManageViewModel: NSObject {

    func logOutSession(id: String,_ completion: ((_ success: Bool,_ message: String) -> Void)?) {
        ApiCall.sameDeviceIdLogout(id: id) { data, status in
            completion?(true, "")
        } failure: { error, data, statusCode in
            if let `data` = data, let model: FailedResponse = DataParser.parseReponseToModel(data) {
                if model.message != nil {
                    completion?(false, model.message ?? "")
                    return
                }
            }
            completion?(false, error?.localizedDescription ?? "")
        }

    }
    
    func sameDeviceLogOut(id: String, _ completion: ((_ success: Bool,_ message: String) -> Void)?) {
        ApiCall.sameDeviceIdLogout(id: id) { [weak self] data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseFailLoginResponse(data!) {
                completion?(true, "")
//                weak.showSimpleAlertWithMessage(data.message ?? "")
            }
        } failure: { [weak self] error, data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseFailLoginResponse(data!) {
                print(data.message ?? "")
                completion?(false, data.message ?? "")
            }
            debugPrint(error?.localizedDescription ?? "")
            
        }
    }
}
