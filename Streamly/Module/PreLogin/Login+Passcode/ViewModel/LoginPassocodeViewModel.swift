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
    func apiUpdateNameSuccess()
    func apiFailure(_ message: String,_ status: Int)
}
class LoginPassocodeViewModel: NSObject {

    var passode = ""
    var responseData: LoginResponse?
    var removeIdKey = ""
    var deviceListInfo: Loggedindeviceinfolist?
    var personaldeviceid = ""
    weak var delegate: APIResponseDelegate?
    
    
    func updateDeviceName(_ name: String) {
        guard !personaldeviceid.isEmpty else { return }
        ApiCall.updateDeviceName(personaldeviceid, name) { data, status in
            self.delegate?.apiUpdateNameSuccess()
        } failure: { error, data, statusCode in
            print(error?.localizedDescription ?? "")
        }

    }
    func loginWithPasscode() {
        let param = ["passcode": passode, "deviceid": deviceId ?? ""]
        ApiCall.loginWithPasscode(param: param) { [weak self] data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseLoginResponse(data!) {
                if data.success == true {
                    Utility.setBoolInUserDefaultData(value: data.userrole != "user", key: Constant.UserDefault.isTierUser)
                    weak.personaldeviceid = data.personaldeviceid ?? ""
                    Utility.setStringObject(value: data.personaldeviceid ?? "", key: Constant.UserDefault.mypersonaldeviceid)
                    Utility.setStringObject(value: data.deviceType ?? "", key: Constant.UserDefault.myDeviceType)
                    Utility.setStringObject(value: data.access_token ?? "", key: Constant.UserDefault.token)
                    Utility.setStringObject(value: data.user?.zipcode ?? "", key: Constant.UserDefault.zipCode)
                    Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isLogin)
                    Utility.setBoolInUserDefaultData(value: data.personaldevicenamePopup ?? false, key: Constant.UserDefault.isDeviceNameAdded)
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
                if let deviceInfo = data.loggedindeviceinfolist {
                    weak.deviceListInfo = deviceInfo
                }
                if let removeKeyAvailAble = data.data {
                    weak.removeIdKey = removeKeyAvailAble
                }
                print(data.message ?? "")
                weak.delegate?.apiFailure(data.message ?? "", data.response_code ?? 0)
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
                weak.delegate?.apiFailure(data.message ?? "", data.response_code ?? 0)
            }
            debugPrint(error?.localizedDescription ?? "")
        }
    }
}
