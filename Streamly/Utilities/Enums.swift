//
//  Enums.swift
//  Streamly
//
//  Created by Rohit Singh on 5/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import UIKit

//MARK:- enum for validation message
enum ValidationErrorMessages:String
{
    case user_name_empty
    case email_empty
    case email_invalid
    case mobile_empty
    case mobile_invalid
    case address_empty
    case password_empty
    case password_invalid

    func descriptipon() -> String {
        switch self{
        case .user_name_empty:return "Please enter user name"
        case .email_empty:return "Please enter an email address"
        case .email_invalid:return "Please enter an valid email address"
        case .mobile_empty:return "Please enter mobile number"
        case .mobile_invalid:return "Please enter valid mobile number"
        case .address_empty:return "Please Select address"
        case .password_empty:return "Please enter password"
        case .password_invalid:return "Password should be min \(password_min_limit) character and max \(password_max_limit)"
    }
    }

}

enum AppStoryboard : String {

    /// we can add number if name
    case Main

    var instance : UIStoryboard {
        return UIStoryboard(name: self.rawValue, bundle: Bundle.main)
    }

    func viewController<T : UIViewController>(viewControllerClass : T.Type) -> T {
        let storyboardID = (viewControllerClass as UIViewController.Type).storyboardID
        return instance.instantiateViewController(withIdentifier: storyboardID) as! T
    }
    

}
