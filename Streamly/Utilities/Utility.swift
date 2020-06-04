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

      // MARK: Convenience

        /*
             A method demonstrating how to encapsulate a `UISearchController` for presentation in, for example, a `UITabBarController`
        */
    static func packagedSearchController() -> UIViewController {
        // Load a `SearchResultsViewController` from its storyboard.
        let storyboard = UIStoryboard(name: "Main", bundle: nil)
        guard let searchResultsController = storyboard.instantiateViewController(withIdentifier: "SearchVC") as? SearchVC else {
            fatalError("Unable to instatiate a SearchResultsViewController from the storyboard.")
        }

        /*
         Create a UISearchController, passing the `searchResultsController` to
         use to display search results.
         */
        let searchController = UISearchController(searchResultsController: searchResultsController)
        searchController.searchResultsUpdater = searchResultsController
        searchController.searchBar.placeholder = NSLocalizedString("Enter keyword (e.g. Channels)", comment: "")


        // Contain the `UISearchController` in a `UISearchContainerViewController`.
        let searchContainer = UISearchContainerViewController(searchController: searchController)
        searchContainer.title = NSLocalizedString("Search", comment: "")


        // Finally contain the `UISearchContainerViewController` in a `UINavigationController`.
        let searchNavigationController = UINavigationController(rootViewController: searchContainer)
        return searchNavigationController
    }

}
