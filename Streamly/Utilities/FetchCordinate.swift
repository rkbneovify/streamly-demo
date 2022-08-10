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
    var authorizationCallback: (() -> Void)?
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
    
    
    //func checkLocationServices() {
       // if CLLocationManager.locationServicesEnabled() {
            
       //     checklocationAuthorization()
       // }
        
    //}

    // Mark:- locationmanger delegate Method
    func locationManager(_ manager: CLLocationManager, didUpdateLocations locations: [CLLocation]) {
        guard let location: CLLocationCoordinate2D = manager.location?.coordinate else { return }
        // set the value of lat and long
        latitude = location.latitude
        longitude = location.longitude
        let locationTupple = (latitude: latitude, longtitude: longitude)
        if let lastLocation = UserDefaults.standard.value(forKey: Constant.UserDefault.lastLocation) as? [String: Any] {
            if lastLocation["latitude"] as? Double == locationTupple.latitude && lastLocation["longtitude"] as? Double == locationTupple.longtitude {
                print("Location Exist for geoFence")
                return
            } else {
                print("Location New for geoFence")
                callthePostAddressGeoFence?()
            }
        } else {
            print("Location New for geoFence")
            callthePostAddressGeoFence?()
        }
       
    }

    func locationManager(_ manager: CLLocationManager, didFailWithError error: Error) {
        debugPrint(error)
    }
    
    public func locationManager(_ manager: CLLocationManager,
                                   didChangeAuthorization status: CLAuthorizationStatus) {
        checklocationAuthorization()
    }
    
}

 // MARK: - check location Autherization
extension Address {
    
    func checklocationAuthorization() {
        if CLLocationManager.locationServicesEnabled() {
            switch CLLocationManager.authorizationStatus() {
            case .notDetermined, .restricted, .denied:
                print("No access")
                locationManager.requestWhenInUseAuthorization()
                locationManager.requestLocation()
            case .authorizedAlways, .authorizedWhenInUse:
                print("Access")
                authorizationCallback?()
            @unknown default:
                fatalError()
            }
        } else {
            locationManager.requestWhenInUseAuthorization()
            print("Location services are not enabled")
        }
    }
}
