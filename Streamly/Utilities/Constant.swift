//
//  Constant.swift
//  Streamly
//
//  Created by Rohit Singh on 5/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import UIKit
import os

//MARK:- Validation Constants
let passwordMinLimit = 6
let passwordMaxLimit = 10
let mobileMaxLimit = 15
let mobileMinLimit = 8
let os = ProcessInfo().operatingSystemVersion
let userAgent = "(Apple TV; CPU TV OS \(os.majorVersion) like Mac OS X)"

//Header count EPG 4 * 48 + 1(Header date)
let daysData = 193

//MARK:-: Constant Text
let appName = "Streamly"

///Appdelegate refrence
let appDelegate = UIApplication.shared.delegate as? AppDelegate

///Device Info
let deviceId = UIDevice.current.identifierForVendor?.uuidString
//let deviceModel = UIDevice.modelName



class Constant: NSObject
{
    class func navigateToRootLogin()
    {
        guard let app = appDelegate else {return}
        let loginVc = LoginVC.instantiateFromAppStoryboard(appStoryboard: .Main)
//        let loginVc = HomeNewVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        let rootViewController = appDelegate?.window?.rootViewController as? UINavigationController
        rootViewController?.setViewControllers([loginVc], animated: false)
        rootViewController?.isNavigationBarHidden = true
        app.window?.rootViewController!.removeFromParent()
        app.window?.rootViewController!.view.removeFromSuperview()
        app.window?.rootViewController = nil
        app.window?.rootViewController = rootViewController
    }

    class func navigateToRootHome()
    {
        guard let app = appDelegate else {return}
        let homeVC = HomeNewVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        let rootViewController = appDelegate?.window?.rootViewController as? UINavigationController
        rootViewController?.setViewControllers([homeVC], animated: false)
        rootViewController?.isNavigationBarHidden = true
        app.window?.rootViewController!.removeFromParent()
        app.window?.rootViewController!.view.removeFromSuperview()
        app.window?.rootViewController = nil
        app.window?.rootViewController = rootViewController
    }
    
    class func navigateToHotelRoomHome()
    {
        guard let app = appDelegate else {return}
        let homeVC = RoomViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        let rootViewController = appDelegate?.window?.rootViewController as? UINavigationController
        rootViewController?.setViewControllers([homeVC], animated: false)
        rootViewController?.isNavigationBarHidden = true
        app.window?.rootViewController!.removeFromParent()
        app.window?.rootViewController!.view.removeFromSuperview()
        app.window?.rootViewController = nil
        app.window?.rootViewController = rootViewController
    }

    class func navigateToNoNetwork()
    {
        guard let app = appDelegate else {return}
        let noInternetVC = NoInternetVC.instantiateFromAppStoryboard(appStoryboard: .Main)
        let rootViewController = appDelegate?.window?.rootViewController as? UINavigationController
        rootViewController?.setViewControllers([noInternetVC], animated: false)
        rootViewController?.isNavigationBarHidden = true
        app.window?.rootViewController!.removeFromParent()
        app.window?.rootViewController!.view.removeFromSuperview()
        app.window?.rootViewController = nil
        app.window?.rootViewController = rootViewController
    }


    class func logOut()
    {
        Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
        Utility.logOut()
        Switcher.updateRootVC()
    }

    class func fetchPixel() -> String
    {
        return "\(UIScreen.main.nativeBounds.width)x\(UIScreen.main.nativeBounds.width)"
    }

    struct UserDefault {
        
        static let isLogin = "isLogin"
        static let userName = "userName"
        static let token = "tokan"
        static let createdAt = "createdAt"
        static let email = "email"
        static let password = "password"
        static let userField = "userField"
        static let zipCode = "zipCode"
        static let isRoomExist = "isRoomExist"
        static let isRoomSelected = "isRoomSelected"

    }

    //MARK:-: Constant Url & Keys

    static var drmXMl = ""

    static let drmRequestURL = "https://fp-keyos.licensekeyserver.com/getkey"
    static let drmCertificateURL = "https://fp-keyos.licensekeyserver.com/cert/9754e0cfddc682c21216de3eb4d11885.der"
    static let baseURL = "https://streamlytv.com/api/"

  
}
