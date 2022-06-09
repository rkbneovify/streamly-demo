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
    @IBOutlet var saveButton: GradientBUtton!
    @IBOutlet var bgImageView: UIImageView!
    @IBOutlet var logOutButton: GradientBUtton!
    
    var viewModel = LoginPassocodeViewModel()
    
    override var preferredFocusedView: UIView? {
        get {
            return self.nameTextField
        }
    }
    
    
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
        self.logOutButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.logOutButton.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.logOutButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.logOutButton.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.logOutButton.setTitleColor(UIColor.white, for: .focused)
        self.logOutButton.setTitleColor(UIColor.white, for: .highlighted)
        
        // next button
        self.saveButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.saveButton.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.saveButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.saveButton.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.saveButton.setTitleColor(UIColor.white, for: .focused)
        self.saveButton.setTitleColor(UIColor.white, for: .highlighted)
        
        // logOut Action
        if #available(tvOS 14.0, *) {
            saveButton.addAction(UIAction(handler: { [weak self] _ in
                guard let self = self else { return }
                if (self.nameTextField.text?.isEmpty ?? false) {
                    self.showSimpleAlertWithMessage("Please enter the name")
                    return
                }
                self.viewModel.updateDeviceName(self.nameTextField.text ?? "")
            }), for: .primaryActionTriggered)
        } else {
            // Fallback on earlier versions
            saveButton.addTarget(self, action: #selector(saveAction(_:)), for: .primaryActionTriggered)
        }
        
        // logOut Action
        if #available(tvOS 14.0, *) {
            logOutButton.addAction(UIAction(handler: { _ in
                self.viewPopups()
            }), for: .primaryActionTriggered)
        } else {
            // Fallback on earlier versions
            logOutButton.addTarget(self, action: #selector(logOutAction(_:)), for: .primaryActionTriggered)
        }
    }
    
    
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if nameTextField.isFocused {
            self.logOutButton.backgroundColor = .white
            self.saveButton.backgroundColor = .white
        } else if logOutButton.isFocused {
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.saveButton.layer.borderColor = UIColor.clear.cgColor
                self.logOutButton.layer.borderColor = UIColor.gray.cgColor
            }, completion: nil)
        } else if saveButton.isFocused {
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.logOutButton.layer.borderColor = UIColor.clear.cgColor
                self.saveButton.layer.borderColor = UIColor.gray.cgColor
            }, completion: nil)
        }  else {
            //  self.saveButton.setImage(UIImage(named: "arrowRound"), for: .normal)
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
    
    @objc func logOutAction(_ sender: UIButton) {
        viewPopups()
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
        if Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isTierUser) {
            Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isRoomExist)
            Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isRoomSelected)
            Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
            openSearchVC()
        } else if UserDefaults.standard.bool(forKey: Constant.UserDefault.isLogin) {
            Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
            Switcher.updateRootVC()
        }
    }
    
    func apiFailure(_ message: String, _ status: Int) {
        
    }
}



// MARK: - Other Controller
extension UpdateDeviceViewController {
    // MARK: - Open Other Controller
    fileprivate func openSearchVC() {
        let vc = RoomViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        if navigationController == nil {
            let nav = UINavigationController(rootViewController: vc)
            present(nav, animated: true, completion: nil)
            return
        }
        self.navigationController?.pushViewController(vc, animated: true)
    }
    
    private func viewPopups()
    {
        let popUPViewsVC = PopUPViewsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        popUPViewsVC.modalPresentationStyle = .overCurrentContext
        popUPViewsVC.item = 4
        self.present(popUPViewsVC, animated:true, completion:nil)
    }
}
