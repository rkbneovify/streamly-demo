//
//  FetchCordinate.swift
//  Streamly
//
//  Created by Rohit Singh on 8/5/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import CoreLocation

final class Address:NSObject, CLLocationManagerDelegate
{
    static let sharedInstance = Address()

    // set these outside of any functions in your class
    let locationManager = CLLocationManager() // create Location Manager object
    var latitude : Double?
    var longitude : Double?
    var callthePostAddressGeoFence: (() -> Void)?
    func cordnates()
    {
        // For use in foreground
        // You will need to update your .plist file to request the authorization
        self.locationManager.requestWhenInUseAuthorization()

        if CLLocationManager.locationServicesEnabled() {
            locationManager.delegate = self
            locationManager.desiredAccuracy = kCLLocationAccuracyNearestTenMeters
            locationManager.requestLocation()

        }
        
    }

    // Add this function to your program
    func locationManager(_ manager: CLLocationManager, didUpdateLocations locations: [CLLocation]) {
        guard let location: CLLocationCoordinate2D = manager.location?.coordinate else { return }
        // set the value of lat and long
        latitude = location.latitude
        longitude = location.longitude
        callthePostAddressGeoFence?()
    }

    func locationManager(_ manager: CLLocationManager, didFailWithError error: Error) {
        debugPrint(error)
    }
    
}
