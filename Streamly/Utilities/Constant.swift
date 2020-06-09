//
//  Constant.swift
//  Streamly
//
//  Created by Rohit Singh on 5/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import UIKit


//Mark:- Validation Constants
let passwordMinLimit = 6
let passwordMaxLimit = 10
let mobileMaxLimit = 15
let mobileMinLimit = 8

//Mark:- Constant Text
let appName = "Streamly"

///Appdelegate refrence
let appDelegate = UIApplication.shared.delegate as? AppDelegate

///Device Info
let deviceId = UIDevice.current.identifierForVendor?.uuidString
let deviceModel = UIDevice.modelName



class Constant: NSObject
{
    class func navigateToRootLogin()
    {
        guard let app = appDelegate else {return}
        let loginVc = LoginVC.instantiateFromAppStoryboard(appStoryboard: .Main)
        let rootViewController = appDelegate?.window!.rootViewController as! UINavigationController
        rootViewController.setViewControllers([loginVc], animated: false)
        rootViewController.isNavigationBarHidden = true
        app.window?.rootViewController!.removeFromParent()
        app.window?.rootViewController!.view.removeFromSuperview()
        app.window?.rootViewController = nil
        app.window?.rootViewController = rootViewController
    }

    class func navigateToRootHome()
    {
        guard let app = appDelegate else {return}
        let loginVc = HomeVC.instantiateFromAppStoryboard(appStoryboard: .Main)
        let rootViewController = appDelegate?.window!.rootViewController as! UINavigationController
        rootViewController.setViewControllers([loginVc], animated: false)
        rootViewController.isNavigationBarHidden = true
        app.window?.rootViewController!.removeFromParent()
        app.window?.rootViewController!.view.removeFromSuperview()
        app.window?.rootViewController = nil
        app.window?.rootViewController = rootViewController
    }

    struct UserDefault {
        
        static let isLogin = "isLogin"
        static let userName = "userName"
        static let token = "tokan"
        static let createdAt = "createdAt"

    }
}
