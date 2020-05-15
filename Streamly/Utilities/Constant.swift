//
//  Constant.swift
//  Streamly
//
//  Created by Rohit Singh on 5/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import UIKit

//Mark:- App colors
let spinnerBackgroundColor = UIColor(redd: 192.0, greenn: 192.0, bluee: 192.0, alphaa: 0.5)

//Mark:- Validation Constants
let passwordMinLimit = 6
let passwordMaxLimit = 10
let mobileMaxLimit = 15
let mobileMinLimit = 8

//Mark:- Constant Text
let appName = "Streamly"

///Appdelegate refrence
let appDelegate = UIApplication.shared.delegate as? AppDelegate


class Constant: NSObject
{
    class func navigateToRootLogin()
    {
        guard let app = appDelegate else {return}
        let loginVc = LoginVC.instantiateFromAppStoryboard(appStoryboard: .main)
        let rootViewController = appDelegate?.window!.rootViewController as! UINavigationController
        rootViewController.setViewControllers([loginVc], animated: false)
        rootViewController.isNavigationBarHidden = true
        app.window?.rootViewController!.removeFromParent()
        app.window?.rootViewController!.view.removeFromSuperview()
        app.window?.rootViewController = nil
        app.window?.rootViewController = rootViewController
    }

}
