//
//  LoginWithQRViewModel.swift
//  Streamly
//
//  Created by Aditya Joshi on 07/10/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

protocol LoginWithQRDelegate: AnyObject {
    func apiSuccess()
    func apiSuccessWithData<T>(data: T)
    func apiFailed(_ message: String)
}
class LoginWithQRViewModel: NSObject {

    enum PollinInAPI {
        case isGetQR
        case isScanQr
        case isLogin
    }
    weak var delegate: LoginWithQRDelegate?
    var currentAPIPolling: PollinInAPI = .isGetQR
    
    private func getQrImage() {
        ApiCall.getQRDataForLogin { [weak self] data, status in
            guard let weak = self else { return }
            if let data:QRModel = DataParser.parseReponseToModel(data!) {
                if data.status == 1 {
                    Utility.setStringObject(value: data.key ?? "", key: Constant.UserDefault.getQrCodeKey)
                    weak.delegate?.apiSuccessWithData(data: data)
                    weak.currentAPIPolling = .isScanQr
                }
               
            }
        } failure: { [weak self] error, data, status in
            guard let weak = self else { return }
            if let data:QRModel = DataParser.parseReponseToModel(data!) {
                weak.delegate?.apiFailed(data.msg ?? "")
                return
            }
            weak.delegate?.apiFailed(error?.localizedDescription ?? "")
        }
    }
    
    private func scanQRCodeFromDevice(key: String) {
        ApiCall.mobileScanQRCode(key: key){ [weak self] data, status in
            guard let weak = self else { return }
            if let data:QRModel = DataParser.parseReponseToModel(data!) {
                if data.status == 1 {
                    weak.currentAPIPolling = .isLogin
                } else if data.status == 2 { // Qr is Expired
                    weak.currentAPIPolling = .isGetQR
                }
                weak.delegate?.apiSuccess()
            }
        } failure: { [weak self] error, data, status in
            guard let weak = self else { return }
            if let data = data, let response:QRModel = DataParser.parseReponseToModel(data) {
                if response.status == 2 { // Qr is Expired
                    weak.currentAPIPolling = .isGetQR
                } else {
                    weak.delegate?.apiFailed(response.msg ?? "")
                }
                return
            }
            weak.delegate?.apiFailed(error?.localizedDescription ?? "")
        }
    }
    
    private func loginWithQRCode(key: String) {
        ApiCall.entryLogin(key: key){ [weak self] data, status in
            guard let weak = self else { return }
            if let data:LoginResponse = DataParser.parseReponseToModel(data!) {
                weak.delegate?.apiSuccessWithData(data: data)
            }
        } failure: { [weak self] error, data, status in
            guard let weak = self else { return }
            if let data:FailedResponse = DataParser.parseReponseToModel(data!) {
                if data.response_code == 402 {
                    weak.delegate?.apiFailed(data.message ?? "")
                } else if data.response_code == 401 {
                    Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                    Utility.logOut()
                    Switcher.updateRootVC()
                } else {
                    weak.delegate?.apiFailed(data.message ?? "")
                }
                return
            }
            weak.delegate?.apiFailed(error?.localizedDescription ?? "")
        }
    }
    
    func sameDeviceLogOut(id: String) {
        ApiCall.sameDeviceIdLogout(id: id) { [weak self] data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseFailLoginResponse(data!) {
                weak.delegate?.apiSuccessWithData(data: data)
            }
        } failure: { [weak self] error, data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseFailLoginResponse(data!) {
                print(data.message ?? "")
                weak.delegate?.apiFailed(data.message ?? "")
            }
            debugPrint(error?.localizedDescription ?? "")
           
        }
    }
    
    // MARK: - Polling Logics
    func pollingInAPI() {
        switch currentAPIPolling {
        case .isGetQR:
            getQrImage()
            print("Get QrCode Image API")
        case .isScanQr:
            let qrKey = Utility.retriveStringObject(key: Constant.UserDefault.getQrCodeKey)
            scanQRCodeFromDevice(key: qrKey)
            print("Scan QrCode Image API")
        case .isLogin:
            let qrkey = Utility.retriveStringObject(key: Constant.UserDefault.getQrCodeKey)
            loginWithQRCode(key: qrkey)
            print("Is Login With QR Code")
            break
        }
    }
}
