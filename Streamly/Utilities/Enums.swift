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

//MARK:-: API endPoints

enum API:String {

    case getConnectedDevice = "auth/connecteddevice"
    case getContactInfo     = "auth/support"
    case logOut             = "auth/logout"
    case login              = "auth/login"
    case privacyData        = "auth/privacypol"
    case getSearchData      = "auth/search"
    case getEpgData         = "auth/channeldayschdeulepaginateoffsettz"
    case getChannelGenerics = "auth/channelgenre"
    case getChannelList        = "auth/channellist"
    case getTermsData       = "auth/termofuse"
    case getRecentData      = "auth/my_recents"
    case addToRecent        = "auth/addtorecent"
    case getGeoFenceData    = "auth/geofence"
    case getQrCodeData      = "qrcode"
    case postOutsideAdress  = "auth/outsidelocationplay"
    case setCallSign        = "auth/ga"
    case setplayTimer       = "auth/ga/duration"
    case playererror        = "auth/ga/playererror"
    case getdrmkey          = "auth/getdrmkey"
    case getMyChannel       = "auth/my_favorites"
    case loginWithPasscode  = "auth/login-with-passcode"
    case sameDeviceIdLogout = "auth/same-device-id-logout"
    case syncSessionWithRoom = "auth/sync-session-with-room"
    case getHotelRooms = "auth/get-hotelrooms"
    case generateQrForLogin = "login/create/qrcode"
    case entryLogin         = "login/entry/login"
    case mobileDeviceScanCode = "login/scan/qrcode"
    case personalDeviceName = "auth/personaldevicename"
}

/// for settings childView

enum CustomPopUp:Int {
    
    case profile = 0, registrdDevice, support,policies,logOut,terms
}

/// for Categories
enum CategoriesSelect:Int {

    case all = 0, recent, myChannels
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
