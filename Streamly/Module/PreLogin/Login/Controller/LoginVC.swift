//
//  LoginVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
import AVKit

class LoginVC: UIViewController {
    
    //MARK: Outlets
    @IBOutlet weak var userIDField:     UITextField!
    @IBOutlet weak var passwordField:   UITextField!
    @IBOutlet weak var rembemberMe:     UIButton!
    @IBOutlet weak var needHelpBtn:     UIButton!
    @IBOutlet weak var registerNowBtn:  UIButton!
    @IBOutlet weak var signBtn:         GradientBUtton!
    @IBOutlet weak var terms:           UIButton!
    @IBOutlet weak var dontHaveAClabel: UILabel!
    @IBOutlet weak var loginWithPasscode: GradientBUtton!
    @IBOutlet weak var loginWithQRButton: UIButton!
    
    //MARK: Properties
    var remeberMeFlag:Bool?
    {
        didSet {
            if remeberMeFlag ?? false
            {
                if let image = UIImage(named: "check")
                {
                    self.rembemberMe.setBackgroundImage(image, for: .normal)
                    self.rembemberMe.setBackgroundImage(image, for: .focused)
                    self.rembemberMe.setBackgroundImage(image, for: .highlighted)
                }
                
            }
            else {
                self.rembemberMe.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .normal)
                self.rembemberMe.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .focused)
                self.rembemberMe.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .highlighted)
            }
        }
    }
    
    var privacyData:PrivacyResponseData?
    var logOut:LogoutResponseData?
    lazy var viewModel: ContactDataViewModel = {
        return ContactDataViewModel()
    }()
    var termsData = ""
    var qrData:QRResponse?
    lazy var qrModel: QRViewModel = {
        return QRViewModel()
    }()
    
    //MARK: Lifecycle Methods
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        self.initialSetup()
        self.remeberMeFlag = false
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        dontHaveAClabel.isHidden = true
        showORHideVisibility(isShow: false)
        ApiCall.getQRVisbility { data, status in
            if let model = data, let data: VisbilityInfo = DataParser.parseReponseToModel(model) {
                Utility.setBoolInUserDefaultData(value: data.visible ?? false, key: Constant.UserDefault.isVisibility)
                self.showORHideVisibility(isShow: data.visible ?? false)
            }
        } failure: { error, data, statusCode in
            print(error?.localizedDescription ?? "")
        }
    }
    
    func showORHideVisibility(isShow: Bool) {
        loginWithPasscode.isHidden = !isShow
        loginWithQRButton.isHidden = !isShow
        terms.isHidden = !isShow
    }
    
    //MARK: Helpers
    func initialSetup()
    {
        self.needHelpBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .normal)
        self.needHelpBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .focused)
        self.needHelpBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .highlighted)
        
        self.registerNowBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .normal)
        self.registerNowBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .focused)
        self.registerNowBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .highlighted)
        self.terms.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .normal)
        self.terms.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .focused)
        self.terms.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .highlighted)
        
        
        self.loginWithPasscode.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.loginWithPasscode.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.loginWithPasscode.setTitleColor(UIColor.white, for: .focused)
        self.loginWithPasscode.setTitleColor(UIColor.white, for: .highlighted)
        
        self.loginWithQRButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.loginWithQRButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.loginWithQRButton.setTitleColor(UIColor.white, for: .focused)
        self.loginWithQRButton.setTitleColor(UIColor.white, for: .highlighted)
        
        self.signBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.signBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.signBtn.setTitleColor(UIColor.white, for: .focused)
        self.signBtn.setTitleColor(UIColor.white, for: .highlighted)
        
        DispatchQueue.global(qos: .background).async
        {
            self.makeAPiCallTerms()
            
        }
        DispatchQueue.global(qos: .background).async
        {
            self.qrmakeAPiCall()
            
        }
        
        
    }
    
    private func checkRemebermeValue()
    {
        self.userIDField.text = Utility.retriveStringObject(key: Constant.UserDefault.userField)
        self.passwordField.text = Utility.retriveStringObject(key: Constant.UserDefault.password)
    }
    
    private func rembermeCheck()
    {
        if remeberMeFlag ?? false{
            remeberMeFlag = false
        }
        else {
            remeberMeFlag = true
        }
    }
    private func openTermsPopUp()
    {
        let popUPViewsVC = PopUPViewsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        popUPViewsVC.modalPresentationStyle = .overCurrentContext
        popUPViewsVC.item = 5
        popUPViewsVC.termsdata = self.termsData
        self.present(popUPViewsVC, animated:true, completion:nil)
    }
    
    private func makeAPiCallTerms()
    {
        viewModel.getTerms()
        // viewModel.delegate = self
        self.initVMPrivacy()
    }
    
    private func initVMPrivacy()
    {
        viewModel.privacyResponseData = { privacyModel in
            self.privacyData = privacyModel
            guard let dataprivacy = self.privacyData else {
                return
            }
            if dataprivacy.success ?? false
            {
                self.termsData = dataprivacy.data ?? ""
            }
            
        }
    }
    
    private func qrmakeAPiCall()
    {
        qrModel.getQrData()
        self.qrinitVM()
    }
    
    private func qrinitVM()
    {
        qrModel.qrData = { qryModel in
            self.qrData = qryModel
            
            
        }
    }
    
    //MARK: Action
    @IBAction func btnActions( _ sender:UIButton)
    {
        
        // tag 2 for rembember me
        // tag 3 for Need Help
        // tag 4 for Sign IN
        // tag 5 for Register Now
        
        if sender.tag == 2 {
            self.rembermeCheck()
        }
        else if sender.tag == 3
        {
            let popUPViewsVC = PopUPViewsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
            popUPViewsVC.modalPresentationStyle = .overCurrentContext
            popUPViewsVC.item = 2
            popUPViewsVC.isfromLogin = true
            //popUPViewsVC.contactInfo = contactInfo
            self.present(popUPViewsVC, animated:true, completion:nil)
        }
        else if sender.tag == 4
        {
            // check login fields
            self.isValid()
            
        }
        else if sender.tag == 5
        {
            if Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isVisibility) {
                let popUPViewsVC = QRViewVC.instantiateFromAppStoryboard(appStoryboard: .Main)
                popUPViewsVC.isFetchData = ( qrData == nil) ? false : true
                popUPViewsVC.qrData = self.qrData
                self.present(popUPViewsVC, animated:true, completion:nil)
            } else {
                let popUPViewsVC = QRViewVC.instantiateFromAppStoryboard(appStoryboard: .Main)
                popUPViewsVC.isFetchData = ( qrData == nil) ? false : true
                popUPViewsVC.qrData = self.qrData
                self.present(popUPViewsVC, animated:true, completion:nil)
//                showAlert(title: "Register Now",withMessage: "Open the link in a browser\nhttps://streamlytv.com/", setFirstButtonTitle: "Ok") { _ in }
            }
            
        }
        else if sender.tag == 6
        {
            self.openTermsPopUp()

        }
        
        else if sender.tag == 7
        {
            // navigate to login with passcode
            let vc = LoginPasscodeVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
            self.navigationController?.pushViewController(vc, animated: true)
            
        } else if sender.tag == 8 {
            // Navigate to login with QR.
            let vc = LoginWithQRViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
            self.navigationController?.pushViewController(vc, animated: true)
        }
    }
}

extension LoginVC
{
    
    /// Open the video player for tutorials.
    private func watchVideoTuts() {
        guard let url = URL(string: "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4") else { return }

            // Create an AVPlayer, passing it the HTTP Live Streaming URL.
            let player = AVPlayer(url: url)

            // Create a new AVPlayerViewController and pass it a reference to the player.
            let controller = AVPlayerViewController()
            controller.player = player

            // Modally present the player and call the player's play() method when complete.
            present(controller, animated: true) {
                player.play()
            }
    }
    
    private func isValid()
    {
        guard self.validateEmail(text: userIDField.text ?? "") == "" else {
            return self.showSimpleAlertWithMessage(self.validateEmail(text: userIDField.text ?? ""))
        }
        guard self.validatePassword(text: passwordField.text ?? "") == "" else {
            return self.showSimpleAlertWithMessage(self.validatePassword(text: passwordField.text ?? ""))
        }
        // call login API
        self.loginAPICall(self.userIDField.text ?? "", self.passwordField.text ?? "")
    }
    
    private func loginAPICall(_ email:String, _ password:String)
    {
        self.showSpinner(onView: self.view)
        let loginrequest = LoginRequest.init()
        loginrequest.email = email
        loginrequest.password = password
        loginrequest.deviceID  = deviceId
        loginrequest.userLogin { [weak self] (response, errorMessage) in
            self?.removeSpinner()
            if response != nil && errorMessage == nil{
                guard let selfStrong = self else {return}
                selfStrong.removeSpinner()
                
                if response.success?.success == true {
                    Utility.setStringObject(value: response.success?.access_token ?? "", key: Constant.UserDefault.token)
                    Utility.setStringObject(value: response.success?.user?.zipcode ?? "", key: Constant.UserDefault.zipCode)
                    if self?.remeberMeFlag ?? false {
                        Utility.setStringObject(value: self?.passwordField.text ?? "", key: Constant.UserDefault.password)
                        Utility.setStringObject(value: self?.userIDField.text ?? "", key: Constant.UserDefault.userField)
                    }
                    
                    Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isLogin)
                    Utility.setBoolInUserDefaultData(value: response.success?.personaldevicenamePopup ?? false, key: Constant.UserDefault.isDeviceNameAdded)
                    Utility.setBoolInUserDefaultData(value: response.success?.userrole != "user", key: Constant.UserDefault.isTierUser)
                    Utility.setStringObject(value: response.success?.user?.name ?? "", key: Constant.UserDefault.userName)
                    Utility.setStringObject(value: response.success?.user?.email ?? "", key: Constant.UserDefault.email)
                    Utility.setStringObject(value: response.success?.user?.createdAt ?? "", key: Constant.UserDefault.createdAt)
                    Utility.setStringObject(value: response.success?.personaldeviceid ?? "", key: Constant.UserDefault.mypersonaldeviceid)
                    Utility.setStringObject(value: response.success?.deviceType ?? "", key: Constant.UserDefault.myDeviceType)
                    selfStrong.removeSpinner()
                    if !(true) {
                        //                        DispatchQueue.main.asyncAfter(deadline: .now() + 0.2, execute: {
                        //                            self?.openTermsPopUp()
                        //                        })
                    }
                    else {
                       /* if let room = response.success?.rooms, response.success?.userrole != "user" {
                            Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isRoomExist)
                            Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isRoomSelected)
                            selfStrong.openSearchVC(room: room, showDeviceName: response.success?.personaldevicenamePopup ?? false)
                        } else*/ if UserDefaults.standard.bool(forKey: Constant.UserDefault.isLogin) {
                            if !(response.success?.personaldevicenamePopup ?? false) {
                                if let room = response.success?.rooms, response.success?.userrole != "user" {
                                    Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isRoomExist)
                                    Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isRoomSelected)
                                    selfStrong.openSearchVC(room: room, showDeviceName: response.success?.personaldevicenamePopup ?? false)
                                } else {
                                    Utility.setBoolInUserDefaultData(value: (response.success?.personaldevicenamePopup ?? false), key: Constant.UserDefault.isDeviceNameAdded)
                                    Switcher.updateRootVC()
                                }
                            } else {
                                selfStrong.updateDeviceName(sessionId: response.success?.personaldeviceid ?? "")
                            }
                        }
                    }
                    
                } else if response.success?.success == false {
                    if let data = response.success?.data { // check session exist.
                        selfStrong.sameDeviceLogOut(id: data)
//                        selfStrong.openAlertForRemoveId(id: data, message: response.success?.message ?? "")
                    } else if !(response.success?.subscribed ?? false) {
                        selfStrong.showAlert(withMessage: response.success?.response_code == 429 ? response.success?.message ?? "" : response.success?.subscribedmsgis ?? "",setFirstButtonTitle: "OK") { _ in
                            //                        Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                            //                        Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                            //                        Utility.logOut()
                            return
                        }
                    } else {
                        selfStrong.showSimpleAlertWithMessage(response.success?.message ?? "")
                    }
                } else if let failureResponse = response.failure {
                    if failureResponse.response_code == 401 { // token expired
                        selfStrong.showAlert(withMessage: "Your session has expired. You have to log in again to proceed further.",setFirstButtonTitle: "Logout") { _ in
                            Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                            Utility.logOut()
                        }
                        return
                    } else if failureResponse.response_code == 402 {
                        selfStrong.showAlert(withMessage: failureResponse.message ?? "",setFirstButtonTitle: "Logout") { _ in
                            Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                            Utility.logOut()
                        }
                        //                        selfStrong.showSimpleAlertWithMessage(failureResponse.message ?? "")
                    } else if let data = failureResponse.data { // check session exist.
                        selfStrong.sameDeviceLogOut(id: data)
                        //selfStrong.openAlertForRemoveId(id: data, message: failureResponse.message ?? "")
                    } else {
                        selfStrong.showSimpleAlertWithMessage(failureResponse.message ?? "")
                    }
                } else {
                    guard let error = errorMessage else{return}
//                    selfStrong.showSimpleAlertWithMessage(error)
                    
                }
            }
            else {
                self?.removeSpinner()
//                self?.showSimpleAlertWithMessage(ValidationErrorMessages.somethingWentWrong.descriptipon())
            }
        }
    }
    
    func sameDeviceLogOut(id: String) {
        ApiCall.sameDeviceIdLogout(id: id) { [weak self] data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseFailLoginResponse(data!) {
                weak.loginAPICall(weak.userIDField.text ?? "", weak.passwordField.text ?? "")
//                weak.showSimpleAlertWithMessage(data.message ?? "")
            }
        } failure: { [weak self] error, data, status in
            guard let weak = self else { return }
            if let data = DataParser.parseFailLoginResponse(data!) {
                print(data.message ?? "")
                weak.showSimpleAlertWithMessage(ValidationErrorMessages.somethingWentWrong.descriptipon())
            }
            debugPrint(error?.localizedDescription ?? "")
            
        }
    }
}

// MARK: - UI Logics
extension LoginVC {
    private func openAlertForRemoveId(id: String, message: String) {
        let alertAction = UIAlertController(title: "", message: message, preferredStyle: .alert)
        alertAction.addAction(UIAlertAction(title: "Yes", style: .default, handler: { [unowned self] _ in
            self.sameDeviceLogOut(id: id)
        }))
        alertAction.addAction(UIAlertAction(title: "No", style: .cancel, handler: nil))
        present(alertAction, animated: true, completion: nil)
    }
}

// MARK: - Other Controllers.
extension LoginVC {
    fileprivate func openSearchVC(room: [Room], showDeviceName: Bool) {
        let vc = RoomViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        vc.viewModel.listOfRoom = room
        vc.viewModel.deviceNamePopup = showDeviceName
        let searchController = UISearchController(searchResultsController: vc)
        searchController.searchResultsUpdater = vc
        searchController.searchBar.placeholder = NSLocalizedString("Enter keyword (e.g. Rooms)", comment: "")
        let searchContainer = UISearchContainerViewController(searchController: searchController)
        searchContainer.title = NSLocalizedString("Search", comment: "")
        self.navigationController?.pushViewController(searchContainer, animated: true)
    }
    
    fileprivate func updateDeviceName(sessionId: String) {
        let vc = UpdateDeviceViewController.instantiateFromAppStoryboard(appStoryboard: .Extended)
        vc.viewModel.personaldeviceid = sessionId
        self.navigationController?.pushViewController(vc, animated: true)
    }
}

extension LoginVC {
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if userIDField.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.terms.layer.borderColor = UIColor.gray.cgColor
                self.loginWithQRButton.layer.borderColor = UIColor.clear.cgColor
                self.registerNowBtn.layer.borderColor = UIColor.gray.cgColor
                self.userIDField.layer.borderColor = UIColor.clear.cgColor
                self.passwordField.layer.borderColor = UIColor.firstGradientColor.cgColor
            }, completion: nil)
            
        } else if passwordField.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.terms.layer.borderColor = UIColor.gray.cgColor
                self.loginWithQRButton.layer.borderColor = UIColor.clear.cgColor
                self.registerNowBtn.layer.borderColor = UIColor.gray.cgColor
                self.passwordField.layer.borderColor = UIColor.clear.cgColor
                self.userIDField.layer.borderColor = UIColor.firstGradientColor.cgColor
            }, completion: nil)
        }
        else if registerNowBtn.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.terms.layer.borderColor = UIColor.gray.cgColor
                self.registerNowBtn.layer.borderColor = UIColor.clear.cgColor
                self.loginWithQRButton.layer.borderColor = UIColor.clear.cgColor
                self.passwordField.layer.borderColor = UIColor.firstGradientColor.cgColor
                self.userIDField.layer.borderColor = UIColor.firstGradientColor.cgColor
            }, completion: nil)
        } else if loginWithQRButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.terms.layer.borderColor = UIColor.gray.cgColor
                self.loginWithQRButton.layer.borderColor = UIColor.clear.cgColor
                self.passwordField.layer.borderColor = UIColor.firstGradientColor.cgColor
                self.userIDField.layer.borderColor = UIColor.firstGradientColor.cgColor
            }, completion: nil)
        }
        else {
            coordinator.addCoordinatedAnimations({ () -> Void in
                if self.terms.isFocused {
                    self.terms.layer.borderColor = UIColor.clear.cgColor
                }
                else {
                    self.terms.layer.borderColor = UIColor.gray.cgColor
                }
                self.loginWithQRButton.layer.borderColor = UIColor.gray.cgColor
                self.registerNowBtn.layer.borderColor = UIColor.gray.cgColor
                self.passwordField.layer.borderColor = UIColor.firstGradientColor.cgColor
                self.userIDField.layer.borderColor = UIColor.firstGradientColor.cgColor
            }, completion: nil)
        }
        
    }
}

