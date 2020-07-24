//
//  Enums.swift
//  Streamly
//
//  Created by Rohit Singh on 5/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import UIKit

//MARK: enum for validation message
enum ValidationErrorMessages:String
{
    case userNameEmpty
    case emailEmpty
    case emailInvalid
    case mobileEmpty
    case mobileInvalid
    case addressEmpty
    case passwordEmpty
    case passwordInvalid
    case somethingWentWrong

    func descriptipon() -> String {
        switch self{
        case .userNameEmpty:return "Please enter user name"
        case .emailEmpty:return "Please enter an email address"
        case .emailInvalid:return "Please enter an valid email address"
        case .mobileEmpty:return "Please enter mobile number"
        case .mobileInvalid:return "Please enter valid mobile number"
        case .addressEmpty:return "Please Select address"
        case .passwordEmpty:return "Please enter password"
        case .passwordInvalid:return "Password should be min \(passwordMinLimit) character and max \(passwordMaxLimit)"
        case .somethingWentWrong:return "Something Went wrong! Please try again"
    }
    }

}

/// for settings childView

enum CustomPopUp:Int {
    
    case profile = 0, registrdDevice, support,policies,logOut,terms
}

/// for Categories
enum CategoriesSelect:Int {

    case all = 11, recent, myChannels,sports,news,movies,kids
}

enum AppStoryboard : String {

    /// we can add number if name
    case Main
    case Extended = "Main+Extended"

    var instance : UIStoryboard {
        return UIStoryboard(name: self.rawValue, bundle: Bundle.main)
    }

    func viewController<T : UIViewController>(viewControllerClass : T.Type) -> T {
        let storyboardID = (viewControllerClass as UIViewController.Type).storyboardID
        return instance.instantiateViewController(withIdentifier: storyboardID) as! T
    }
    

}
