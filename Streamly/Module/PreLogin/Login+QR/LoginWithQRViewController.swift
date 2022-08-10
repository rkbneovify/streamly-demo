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
                Utility.setStringObject(value: data.houroffset ?? "", key: Constant.UserDefault.addressTimeZone)
                Utility.setStringObject(value: "\(data.user?.id ?? 0)", key: Constant.UserDefault.userID)
                Utility.setStringObject(value: data.personaldeviceid ?? "", key: Constant.UserDefault.mypersonaldeviceid)
                Utility.setStringObject(value: data.deviceType ?? "", key: Constant.UserDefault.myDeviceType)
                Utility.setStringObject(value: data.access_token ?? "", key: Constant.UserDefault.token)
                Utility.setStringObject(value: data.user?.zipcode ?? "", key: Constant.UserDefault.zipCode)
                Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: data.personaldevicenamePopup ?? false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.setStringObject(value: data.user?.name ?? "", key: Constant.UserDefault.userName)
                Utility.setStringObject(value: data.user?.email ?? "", key: Constant.UserDefault.email)
                Utility.setStringObject(value: data.user?.createdAt ?? "", key: Constant.UserDefault.createdAt)
                Utility.setBoolInUserDefaultData(value: data.userrole != "user", key: Constant.UserDefault.isTierUser)
                if let removIdKey = data.data {
                    self.viewModel.sameDeviceLogOut(id: removIdKey)
//                    self.openAlertForRemoveId(id: removIdKey, message: data.message ?? "")
                } else if !(data.subscribed ?? false) {
                    self.showAlert(withMessage: data.subscribedmsgis ?? "",setFirstButtonTitle: "Logout") { _ in
//                        Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
//                        Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
//                        Utility.logOut()
                    }
                    return
                } else if UserDefaults.standard.bool(forKey: Constant.UserDefault.isLogin) {
                    if !(data.personaldevicenamePopup ?? false) {
                        if let room = data.rooms, data.userrole != "user" {
                           Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isRoomExist)
                           Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isRoomSelected)
                           openSearchVC(room: room,showDeviceNamePopup: data.personaldevicenamePopup ?? false)
                        } else {
                            Switcher.updateRootVC()
                        }
                    } else {
                        openUpdateDeviceName(session: data.personaldeviceid ?? "")
                    }
                   // Switcher.updateRootVC()
                } else {
                    if !(data.personaldevicenamePopup ?? false) {
                        Switcher.updateRootVC()
                    } else {
                        openUpdateDeviceName(session: data.personaldeviceid ?? "")
                    }
                   // Switcher.updateRootVC()
                }
            }
        } else if let data = data as? FailedResponse { // handle Login failed Response
            self.viewModel.sameDeviceLogOut(id: data.data ?? "")
//            self.openAlertForRemoveId(id: data.data ?? "", message: data.message ?? "")
        }
    }

    func apiFailed(_ message: String,_ status: Int) {
        if status == 479 {
            if let devicelist = viewModel.deviceInfoList {
                self.showAlert(title: appName, withMessage: "Your Account is in use on 5 device. please stop playing on other devices to continue", setTwoButton: true, setFirstButtonTitle: "YES", setSecondButtonTitle: "NO") { _ in
                    self.deviceListScreen(deviceInfo: devicelist)
                }
                return
            }
        } /*else if status == 401 {
            showAlert(withMessage: "Your session has expired. You have to log in again to proceed further.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
        }*/  else if status == 402 {
            showAlert(withMessage: message,setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
            }
        } else if status == 429 {
            showAlert(withMessage: message,setFirstButtonTitle: "OK") { _ in }
        } else if status == 401 {
            showSimpleAlertWithMessage(message)
        } else if !message.isEmpty {
            showSimpleAlertWithMessage(message)
        }
        //showSimpleAlertWithMessage(message)
    }


}

// MARK: - UI Logics
extension LoginWithQRViewController {
    private func openAlertForRemoveId(id: String, message: String) {
        let alertAction = UIAlertController(title: "", message: message, preferredStyle: .alert)
        alertAction.addAction(UIAlertAction(title: "Yes", style: .default, handler: { [unowned self] _ in
            viewModel.sameDeviceLogOut(id: id)
        }))
        alertAction.addAction(UIAlertAction(title: "No", style: .cancel, handler: nil))
        present(alertAction, animated: true, completion: nil)
    }
}

// MARK: - Other Controllers.
extension LoginWithQRViewController {
    fileprivate func openSearchVC(room: [Room], showDeviceNamePopup: Bool) {
        let vc = RoomViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        vc.viewModel.listOfRoom = room
        vc.viewModel.deviceNamePopup = showDeviceNamePopup
        let searchController = UISearchController(searchResultsController: vc)
        searchController.searchResultsUpdater = vc
        searchController.searchBar.placeholder = NSLocalizedString("Enter keyword (e.g. Rooms)", comment: "")
        let searchContainer = UISearchContainerViewController(searchController: searchController)
        searchContainer.title = NSLocalizedString("Search", comment: "")
        self.navigationController?.pushViewController(vc, animated: true)
    }
    
    fileprivate func openUpdateDeviceName(session: String) {
        let vc = UpdateDeviceViewController.instantiateFromAppStoryboard(appStoryboard: .Extended)
        vc.viewModel.personaldeviceid = session
        self.navigationController?.pushViewController(vc, animated: true)
    }
    
    fileprivate func deviceListScreen(deviceInfo: Loggedindeviceinfolist) {
        let vc = DeviceListViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        vc.deviceList = deviceInfo.data ?? []
        vc.isFromLogin = true
        vc.delegate = self
        let searchController = UISearchController(searchResultsController: vc)
        searchController.searchResultsUpdater = vc
        searchController.searchBar.placeholder = NSLocalizedString("Enter keyword (e.g. Device ID or Name)", comment: "")
        let searchContainer = UISearchContainerViewController(searchController: searchController)
        searchContainer.title = NSLocalizedString("Search", comment: "")
        self.navigationController?.pushViewController(searchContainer, animated: true)
    }
}

// MARK: - DeviceListDelegate
extension LoginWithQRViewController: DeviceListDelegate {
    func sessionLogoutSuccess() {
        viewModel.pollingInAPI()
    }
}
