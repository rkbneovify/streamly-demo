//
//  Utility.swift
//  Streamly
//
//  Created by Rohit Singh on 5/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import UIKit

class Utility {

   static func setBoolInUserDefaultData(value: Bool = false, key: String) {
           UserDefaults.standard.set(value, forKey: key)
           UserDefaults.standard.synchronize()
       }

       static func retriveBooleanFromUserDefault(key: String) -> Bool {
           return  UserDefaults.standard.bool(forKey: key)
       }

       static func setStringObject(value: String, key: String) {
           UserDefaults.standard.set(value, forKey: key)
           UserDefaults.standard.synchronize()
       }

       /// clear user default
       class func logOut()
       {
        let domain = Bundle.main.bundleIdentifier!
           UserDefaults.standard.removePersistentDomain(forName: domain)
           UserDefaults.standard.synchronize()
           debugPrint(Array(UserDefaults.standard.dictionaryRepresentation().keys).count)
           debugPrint("LogoutDone")
           Switcher.updateRootVC()
       }

       static func retriveStringObject(key: String) -> String? {

           return  UserDefaults.standard.string(forKey: key)
       }

}
