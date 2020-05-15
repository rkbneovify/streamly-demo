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
let themecolor = UIColor(r: 225, g: 226, b: 115, a: 1)
let bordercolor = UIColor(r: 23, g: 23, b: 23, a: 0.30)

//Mark:- Validation Constants
let password_min_limit = 6
let password_max_limit = 10
let mobile_max_limit = 15
let mobile_min_limit = 8

//Mark:- Constant Text
let appName = "Streamly"

///Appdelegate refrence
let appDelegate = UIApplication.shared.delegate as! AppDelegate


class Constant: NSObject
{
    class func navigateToRootLogin()
    {
        let loginVc = LoginVC.instantiateFromAppStoryboard(appStoryboard: .Main)
        let rootViewController = appDelegate.window!.rootViewController as! UINavigationController
        rootViewController.setViewControllers([loginVc], animated: false)
        rootViewController.isNavigationBarHidden = true
        appDelegate.window!.rootViewController!.removeFromParent()
        appDelegate.window!.rootViewController!.view.removeFromSuperview()
        appDelegate.window!.rootViewController = nil
        appDelegate.window!.rootViewController = rootViewController
    }

}
