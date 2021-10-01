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
    fileprivate func openSearchVC(room: [Room]) {
        let vc = RoomViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        vc.viewModel.listOfRoom = room
        self.navigationController?.pushViewController(vc, animated: true)
    }
}


// MARK - Custom Delegates
/// APIResponseDelegate
extension LoginPasscodeVC: APIResponseDelegate {
    func apiSucess() {
        self.removeSpinner()
        if let room = viewModel.responseData?.rooms {
            openSearchVC(room: room)
        } else if UserDefaults.standard.bool(forKey: Constant.UserDefault.isLogin) {
            Switcher.updateRootVC()
        }
    }
    
    func apiFailure(_ message: String) {
        self.removeSpinner()
        if !viewModel.removeIdKey.isEmpty {
            openAlertForRemoveId()
        } else {
            showSimpleAlertWithMessage(message)
        }
    }
    
    
}
