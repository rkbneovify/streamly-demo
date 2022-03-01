//
//  LoginPasscodeVC.swift
//  Streamly
//
//  Created by Rohit Singh on 8/9/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

class LoginPasscodeVC: UIViewController {

    //MARK: Outlets
    @IBOutlet weak var passCodeField:     UITextField!
    @IBOutlet weak var signBtn:           UIButton!

    private var viewModel = LoginPassocodeViewModel()
    //MARK: LifeCycle
    override func viewDidLoad() {
        super.viewDidLoad()

        self.initialSetup()

        // Do any additional setup after loading the view.
    }

    //MARK: Actions
    @IBAction func signIN(_ button:UIButton) {
        if !(passCodeField.text?.isEmpty ?? false) {
            self.showSpinner(onView: self.view)
            viewModel.passode = passCodeField.text ?? ""
            viewModel.loginWithPasscode()
        }
    }
}

extension LoginPasscodeVC {

    private func initialSetup() {
        viewModel.delegate = self
        self.signBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.signBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.signBtn.setTitleColor(UIColor.white, for: .focused)
        self.signBtn.setTitleColor(UIColor.white, for: .highlighted)
    }
}

extension LoginPasscodeVC {
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if passCodeField.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in

                self.passCodeField.layer.borderColor = UIColor.clear.cgColor
            }, completion: nil)

        }       else {
            coordinator.addCoordinatedAnimations({ () -> Void in

                self.passCodeField.layer.borderColor = UIColor.firstGradientColor.cgColor
            }, completion: nil)
        }

    }
}

// MARK: - UI Logics
extension LoginPasscodeVC {
    private func openAlertForRemoveId() {
        let alertAction = UIAlertController(title: "", message: "Do you want to remove the old session(with this device)", preferredStyle: .alert)
        alertAction.addAction(UIAlertAction(title: "Yes", style: .default, handler: { [unowned self] _ in
            self.viewModel.sameDeviceLogOut(id: viewModel.removeIdKey)
        }))
        alertAction.addAction(UIAlertAction(title: "No", style: .cancel, handler: nil))
        present(alertAction, animated: true, completion: nil)
    }
    
    // MARK: - Open Other Controller
    fileprivate func openSearchVC(room: [Room], showNamePopup: Bool) {
        let vc = RoomViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        vc.viewModel.listOfRoom = room
        vc.viewModel.deviceNamePopup = showNamePopup
        let searchController = UISearchController(searchResultsController: vc)
        searchController.searchResultsUpdater = vc
        searchController.searchBar.placeholder = NSLocalizedString("Enter keyword (e.g. Rooms)", comment: "")
        let searchContainer = UISearchContainerViewController(searchController: searchController)
        searchContainer.title = NSLocalizedString("Search", comment: "")
        self.navigationController?.pushViewController(vc, animated: true)
    }
    
    // MARK: - Open Update name Controller
    fileprivate func openEnterTVNameVC(sessionId: String) {
        let vc = UpdateDeviceViewController.instantiateFromAppStoryboard(appStoryboard: .Extended)
        vc.viewModel.personaldeviceid = sessionId
        self.navigationController?.pushViewController(vc, animated: true)
    }
}


// MARK - Custom Delegates
/// APIResponseDelegate
extension LoginPasscodeVC: APIResponseDelegate {
    func apiUpdateNameSuccess() {
        
    }
    
    func apiSucess() {
        self.removeSpinner()
        if let data = viewModel.responseData?.data { // check session exist.
            viewModel.removeIdKey = data
            self.openAlertForRemoveId()
        } else if !(viewModel.responseData?.subscribed ?? false) {
            self.showAlert(withMessage: viewModel.responseData?.subscribedmsgis ?? "",setFirstButtonTitle: "Logout") { _ in
//                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
//                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
//                Utility.logOut()
                return
            }
        } else if let room = viewModel.responseData?.rooms, viewModel.responseData?.userrole != "user" {
            Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isRoomExist)
            Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isRoomSelected)
            openSearchVC(room: room,showNamePopup: viewModel.responseData?.personaldevicenamePopup ?? false)
        } else if UserDefaults.standard.bool(forKey: Constant.UserDefault.isLogin) {
            // Open controller
            if !(viewModel.responseData?.personaldevicenamePopup ?? false) {
                Switcher.updateRootVC()
            } else {
                openEnterTVNameVC(sessionId: viewModel.personaldeviceid)
            }
        } else {
            self.showSimpleAlertWithMessage(viewModel.responseData?.message ?? "")
        }
        
    }
    
    func apiFailure(_ message: String,_ status: Int) {
        if status == 401 {
            showAlert(withMessage: "Your session has expired. You have to log in again to proceed further.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
            return
        }
        self.removeSpinner()
        if !viewModel.removeIdKey.isEmpty {
            openAlertForRemoveId()
        } else {
            showSimpleAlertWithMessage(message)
        }
    }
    
    
}
