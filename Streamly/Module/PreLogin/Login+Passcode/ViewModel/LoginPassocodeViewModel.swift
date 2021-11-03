//
//  LoginPassocodeViewModel.swift
//  Streamly
//
//  Created by Aditya Joshi on 28/09/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

protocol APIResponseDelegate: AnyObject {
    func apiSucess()
    func apiFailure(_ message: String)
}
class LoginPassocodeViewModel: NSObject {

    var passode = ""
    var responseData: LoginResponse?
    var removeIdKey = ""
    weak var delegate: APIResponseDelegate?
    
    func loginWithPasscode() {
        let param = ["passcode": passode, "deviceid": deviceId ?? ""]
        ApiCall.loginWithPasscode(param: param) { [weak self] data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseLoginResponse(data!) {
                if data.success == true {
                    Utility.setStringObject(value: data.access_token ?? "", key: Constant.UserDefault.token)
                    Utility.setStringObject(value: data.user?.zipcode ?? "", key: Constant.UserDefault.zipCode)
                    Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isLogin)
                    Utility.setStringObject(value: data.user?.name ?? "", key: Constant.UserDefault.userName)
                    Utility.setStringObject(value: data.user?.email ?? "", key: Constant.UserDefault.email)
                    Utility.setStringObject(value: data.user?.createdAt ?? "", key: Constant.UserDefault.createdAt)
                }
                weak.responseData = data
                weak.delegate?.apiSucess()
            }
        } failure: { [weak self] error, data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseFailLoginResponse(data!) {
                if data.response_code == 401 {
                    Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                    Utility.logOut()
                    return
                } else if let removeKeyAvailAble = data.data {
                    weak.removeIdKey = removeKeyAvailAble
                }
                print(data.message ?? "")
                weak.delegate?.apiFailure(data.message ?? "")
            }
            debugPrint(error?.localizedDescription ?? "")
           
        }
    }
    
    func sameDeviceLogOut(id: String) {
        ApiCall.sameDeviceIdLogout(id: id) { [weak self] data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseFailLoginResponse(data!) {
                weak.delegate?.apiSucess()
            }
        } failure: { [weak self] error, data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseFailLoginResponse(data!) {
                print(data.message ?? "")
                weak.delegate?.apiFailure(data.message ?? "")
            }
            debugPrint(error?.localizedDescription ?? "")
           
        }
    }
}
