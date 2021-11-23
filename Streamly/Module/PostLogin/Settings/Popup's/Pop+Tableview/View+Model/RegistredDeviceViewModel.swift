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
        ApiCall.getRegistredDeviceData(success: { data, _
            in
            if let contactModel = DataParser.parseContactData(data!) {
                self.registredDevice!((contactModel))
                //debugPrint(contactModel)
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                if failedData.response_code == 401 {
                    Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                    Utility.logOut()
                    return
                } else if failedData.response_code == 402 {
                    self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                } 
            }
            //debugPrint("failure")
            //self.delegate?.handleError(error?.localizedDescription ?? "")
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
            //debugPrint(error)
        }
        return nil
    }
    
    static func parseLoginResponse(_ data: Data) -> LoginResponse? {
        do {
            let contactModelElement = try JSONDecoder().decode(LoginResponse.self, from: data)


            return contactModelElement
        } catch (let error){
            debugPrint(error.localizedDescription)
        }
        return nil
    }
    
    static func parseReponseToModel<T: Decodable>(_ data: Data) -> T? {
        do {
            let contactModelElement = try JSONDecoder().decode(T.self, from: data)


            return contactModelElement
        } catch (let error){
            debugPrint(error.localizedDescription)
        }
        return nil
    }
    
    static func parseFailLoginResponse(_ data: Data) -> FailedResponse? {
        do {
            let contactModelElement = try JSONDecoder().decode(FailedResponse.self, from: data)


            return contactModelElement
        } catch (let error){
            debugPrint(error.localizedDescription)
        }
        return nil
    }
}



