//
//  LoginVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class LoginVC: UIViewController {

    //MARK:- Outlets
    @IBOutlet weak var userIDField:     UITextField!
    @IBOutlet weak var passwordField:   UITextField!
    @IBOutlet weak var rembemberMe:     UIButton!
    @IBOutlet weak var needHelpBtn:     UIButton!
    @IBOutlet weak var registerNowBtn:  UIButton!
    @IBOutlet weak var signBtn:         GradientBUtton!

    //MARK:- Properties
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

    //MARK:- Lifecycle Methods
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        self.initialSetup()
        self.remeberMeFlag = false
        
    }

    //MARK:- Helpers
    func initialSetup()
    {
        self.needHelpBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .normal)
        self.needHelpBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .focused)
         self.needHelpBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .highlighted)

        self.registerNowBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .normal)
        self.registerNowBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .focused)
        self.registerNowBtn.setBackgroundImage(UIImage.imageWithColor(.cardBgColor), for: .highlighted)

        self.signBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
          self.signBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.signBtn.setTitleColor(UIColor.white, for: .focused)
         self.signBtn.setTitleColor(UIColor.white, for: .highlighted)


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

    //MARK:- Action
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

        }
        else if sender.tag == 4
        {
            // check login fields
            self.isValid()

        }
        else if sender.tag == 5
        {

        }
    }
}

extension LoginVC
{

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
            if response != nil && errorMessage == nil{
                guard let selfStrong = self else {return}
                selfStrong.removeSpinner()
                if response?.success == true {
                    Utility.setStringObject(value: response?.access_token ?? "", key: Constant.UserDefault.token)
                    Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isLogin)
                    Utility.setStringObject(value: response?.user?.name ?? "", key: Constant.UserDefault.userName)
                    Utility.setStringObject(value: response?.user?.created_at ?? "", key: Constant.UserDefault.createdAt)
                    selfStrong.removeSpinner()
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.2, execute: {
                        Switcher.updateRootVC()
                    })
                }
                else {
                    guard let error = response?.message else{return}
                    selfStrong.showSimpleAlertWithMessage(error)
                    
                }
            }
            else {
                self?.removeSpinner()
                self?.showSimpleAlertWithMessage(ValidationErrorMessages.somethingWentWrong.descriptipon())
            }
        }
    }
}


