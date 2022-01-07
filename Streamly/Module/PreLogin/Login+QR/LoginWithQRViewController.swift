//
//  LoginWithQRViewController.swift
//  Streamly
//
//  Created by Aditya Joshi on 07/10/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

class LoginWithQRViewController: UIViewController {
    
    @IBOutlet var qrImageView: UIImageView!
    
    private var viewModel = LoginWithQRViewModel()
    var timer = Timer()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        intialSetup()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        timer.invalidate()
    }
    
    private func intialSetup() {
        viewModel.delegate = self
        viewModel.pollingInAPI()
        self.timer = Timer.scheduledTimer(withTimeInterval: 2, repeats: true, block: { [weak self] _ in
            guard let weak = self else { return }
            weak.viewModel.pollingInAPI()
        })
    }
}

// MARK: - LoginWithQRDelegate
// MARK: - LoginWithQRDelegate
extension LoginWithQRViewController: LoginWithQRDelegate {
    func apiSuccess() {

    }

    func apiSuccessWithData<T>(data: T) {
        if let data = data as? QRModel { // Get QR Image Response
            self.qrImageView.sd_setImage(with: URL(string: data.msg ?? ""), placeholderImage: UIImage(named: "dummyQR"))
        } else if let data = data as? LoginResponse { // Successfully Login response
            if data.success == true {
                timer.invalidate()
                Utility.setStringObject(value: data.sessionid ?? "", key: Constant.UserDefault.mySessionId)
                Utility.setStringObject(value: data.access_token ?? "", key: Constant.UserDefault.token)
                Utility.setStringObject(value: data.user?.zipcode ?? "", key: Constant.UserDefault.zipCode)
                Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.setStringObject(value: data.user?.name ?? "", key: Constant.UserDefault.userName)
                Utility.setStringObject(value: data.user?.email ?? "", key: Constant.UserDefault.email)
                Utility.setStringObject(value: data.user?.createdAt ?? "", key: Constant.UserDefault.createdAt)
                if let removIdKey = data.data {
                    self.openAlertForRemoveId(id: removIdKey)
                } else if let room = data.rooms, data.userrole != "user" {
                    Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isRoomExist)
                    Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isRoomSelected)
                    openSearchVC(room: room)
                } else if UserDefaults.standard.bool(forKey: Constant.UserDefault.isLogin) {
                    openUpdateDeviceName(session: data.sessionid ?? "")
                   // Switcher.updateRootVC()
                } else {
                    openUpdateDeviceName(session: data.sessionid ?? "")
                   // Switcher.updateRootVC()
                }
            }
        } else if let data = data as? FailedResponse { // handle Login failed Response
            self.openAlertForRemoveId(id: data.data ?? "")
        }
    }

    func apiFailed(_ message: String,_ status: Int) {
        if status == 401 {
            showAlert(withMessage: "Your session has expired. You have to log in again to proceed further.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
        }
        //showSimpleAlertWithMessage(message)
    }


}

// MARK: - UI Logics
extension LoginWithQRViewController {
    private func openAlertForRemoveId(id: String) {
        let alertAction = UIAlertController(title: "", message: "Do you want to remove the old session(with this device)", preferredStyle: .alert)
        alertAction.addAction(UIAlertAction(title: "Yes", style: .default, handler: { [unowned self] _ in
            viewModel.sameDeviceLogOut(id: id)
        }))
        alertAction.addAction(UIAlertAction(title: "No", style: .cancel, handler: nil))
        present(alertAction, animated: true, completion: nil)
    }
}

// MARK: - Other Controllers.
extension LoginWithQRViewController {
    fileprivate func openSearchVC(room: [Room]) {
        let vc = RoomViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        vc.viewModel.listOfRoom = room
        self.navigationController?.pushViewController(vc, animated: true)
    }
    
    fileprivate func openUpdateDeviceName(session: String) {
        let vc = UpdateDeviceViewController.instantiateFromAppStoryboard(appStoryboard: .Extended)
        vc.viewModel.sessionID = session
        self.navigationController?.pushViewController(vc, animated: true)
    }
}
