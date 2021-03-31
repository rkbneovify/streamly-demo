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
        //debugPrint(Array(UserDefaults.standard.dictionaryRepresentation().keys).count)
        //debugPrint("LogoutDone")
        Switcher.updateRootVC()
    }
    
    static func retriveStringObject(key: String) -> String {
        
        return  UserDefaults.standard.string(forKey: key) ?? ""
    }
    //
    
    static func getDateFromTimeStamp(_ timeStamp : TimeInterval) -> String? {
        
        let date = NSDate(timeIntervalSince1970: timeStamp)
        
        let dayTimePeriodFormatter = DateFormatter()
        dayTimePeriodFormatter.dateFormat = "hh:mm a"
        dayTimePeriodFormatter.locale = Locale(identifier: "en_US_POSIX")
        dayTimePeriodFormatter.timeZone = TimeZone(abbreviation: "UTC")
        let dateString = dayTimePeriodFormatter.string(from: date as Date)
        let fullNameArr = dateString.components(separatedBy: ":")
        guard let hour = Int(fullNameArr[0]) else {return nil}
        let minA = fullNameArr[1].components(separatedBy: " ")
        guard let min = Int(minA[0]) else {return nil}
        let hrs = String(minA[1])
        return "\(hour):\((min > 29) ? 30:00) \(hrs)"
        //  return TimeInterval((hour * 60 * 60) + (min * 60))
    }
    
    
    static func getDateFromTimeStampByFormat(from interval : TimeInterval, _ format:String = "hh:mm a") -> String?
    {
        let date = NSDate(timeIntervalSince1970: interval)
        let dayTimePeriodFormatter = DateFormatter()
        dayTimePeriodFormatter.dateFormat = format
        dayTimePeriodFormatter.locale = Locale(identifier: "en_US_POSIX")
        dayTimePeriodFormatter.timeZone = TimeZone(abbreviation: "UTC")
        return dayTimePeriodFormatter.string(from: date as Date)
    }

    static func getCurrentDateTime() -> String
    {
        let date = Date()
        let dateFormatter = DateFormatter()
        dateFormatter.dateFormat = "dd/MM/yyyy hh:mm a"
        let current_date = dateFormatter.string(from: date)
        return current_date
    }

    static func convertDateFormat(_ inputDate: String) -> String {

         let olDateFormatter = DateFormatter()
         olDateFormatter.dateFormat = "yyyy-MM-dd HH:mm:ss"

        guard let oldDate = olDateFormatter.date(from: inputDate) else { return "" }

         let convertDateFormatter = DateFormatter()
         convertDateFormatter.dateFormat = "dd/MM/yyyy"

         return convertDateFormatter.string(from: oldDate)
    }

    static func cropImage(image: UIImage, toRect: CGRect) -> UIImage? {
        // Cropping is available trhough CGGraphics
        let cgImage :CGImage! = image.cgImage
        let croppedCGImage: CGImage! = cgImage.cropping(to: toRect)

        return UIImage(cgImage: croppedCGImage)
    }

    static func getProgrammWidth(start: TimeInterval, end: TimeInterval) -> CGFloat {
        let d1 = Date.init(timeIntervalSince1970: start)
        let d2 = Date.init(timeIntervalSince1970: end)
        let intervals = d2.timeIntervalSince(d1)
        let numberOfHours = CGFloat(intervals / 3600)
        return CGFloat(numberOfHours) * (408 * 2)
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
