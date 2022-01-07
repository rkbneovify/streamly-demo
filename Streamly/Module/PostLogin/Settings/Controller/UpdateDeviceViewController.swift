//
//  UpdateDeviceViewController.swift
//  Streamly
//
//  Created by Aditya Joshi on 07/01/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import UIKit

class UpdateDeviceViewController: UIViewController {

    @IBOutlet var nameTextField: UITextField!
    @IBOutlet var skipButton: UIButton!
    @IBOutlet var saveButton: UIButton!
    @IBOutlet var bgImageView: UIImageView!
    
    var viewModel = LoginPassocodeViewModel()

    override func viewDidLoad() {
        super.viewDidLoad()
            setupUI()
    }
    
    private func setupUI() {
        viewModel.delegate = self
        
        // Handle manually
        let menuPressRecognizer = UITapGestureRecognizer()
        menuPressRecognizer.addTarget(self, action: #selector(RoomViewController.menuButtonAction(recognizer:)))
        menuPressRecognizer.allowedPressTypes = [NSNumber(value: UIPress.PressType.menu.rawValue)]
        self.view.addGestureRecognizer(menuPressRecognizer)
    }
    
    override var preferredFocusedView: UIView? {
        get {
            return self.nameTextField
        }
    }
    
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if nameTextField.isFocused {
            self.saveButton.setImage(UIImage(named: "arrowRoundWhiteImage"), for: .normal)
        } else {
            self.saveButton.setImage(UIImage(named: "arrowRound"), for: .normal)
//            nameTextField.layer.borderWidth = 3.0
//            nameTextField.layer.borderColor = UIColor.white.cgColor
        }
    }
}

// MARK: - @objc and @IBactions methods
extension UpdateDeviceViewController {
    @objc func menuButtonAction(recognizer:UITapGestureRecognizer) {
        if let vc = appDelegate?.window?.rootViewController as? UINavigationController, vc.viewControllers.first is UpdateDeviceViewController {
            return
        }
//        Switcher.updateRootVC()
    }
    
    @IBAction func skipAction(_ sender: Any) {
        Switcher.updateRootVC()
    }
    
    @IBAction func saveAction(_ sender: Any) {
        if (nameTextField.text?.isEmpty ?? false) {
            showSimpleAlertWithMessage("Please enter the name")
            return
        }
        viewModel.updateDeviceName(nameTextField.text ?? "")
    }
}

// MARK: - APIResponseDelegate
extension UpdateDeviceViewController: APIResponseDelegate {
    func apiSucess() {
        
    }
    
    func apiUpdateNameSuccess() {
        if let room = viewModel.responseData?.rooms, viewModel.responseData?.userrole != "user" {
            Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isRoomExist)
            Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isRoomSelected)
            openSearchVC(room: room)
        } else if UserDefaults.standard.bool(forKey: Constant.UserDefault.isLogin) {
            Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isDeviceNameAdded)
            Switcher.updateRootVC()
        }
    }
    
    func apiFailure(_ message: String, _ status: Int) {
        
    }
    
    
}



// MARK: - Other Controller
extension UpdateDeviceViewController {
    // MARK: - Open Other Controller
    fileprivate func openSearchVC(room: [Room]) {
        let vc = RoomViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        vc.viewModel.listOfRoom = room
        self.navigationController?.pushViewController(vc, animated: true)
    }
}
