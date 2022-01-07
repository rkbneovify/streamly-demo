//
//  File.swift
//  Streamly
//
//  Created by Rohit Singh on 5/18/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import UIKit

class Switcher {
    //functionToCheckUserLog
    static func updateRootVC() {
        let status = UserDefaults.standard.bool(forKey: Constant.UserDefault.isLogin)
        let isDeviceNameAdded = Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isDeviceNameAdded)
        //debugPrint(status)
        let reachability = Reachability.isConnectedToNetwork()
        //debugPrint(reachability)
        if reachability == true{
            if (status == true) {
                //logedIn
                 let isRoomExist = Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isRoomExist)
                 let roomSelected = Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isRoomSelected)
                if !isDeviceNameAdded {
                    Constant.navigateToDeviceNameScreen()
                } else if isRoomExist && !roomSelected {
                    Constant.navigateToHotelRoomHome()
                 } else {
                    Constant.navigateToRootHome()
                 }
            }
            else {
                // Not login
                Constant.navigateToRootLogin()
            }
            
        }else{
            Constant.navigateToNoNetwork()
        }
        
    }
}
