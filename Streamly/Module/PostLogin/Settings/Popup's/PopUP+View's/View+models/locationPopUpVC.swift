//
//  locationPopUpVC.swift
//  Streamly
//
//  Created by Rajpal Singh on 09/08/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import UIKit
import CoreLocation


class locationPopUpVC: UIViewController {

    @IBOutlet weak var selctionLbl: UILabel!
    @IBOutlet weak var currentLocBtn: UIButton!
    @IBOutlet weak var homeAddBtn: UIButton!
    
    let locationManager = CLLocationManager()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialSetup()
        locationManager.requestWhenInUseAuthorization()
        setupLocationServices()
    
    }
    
    private func setupLocationServices() {
        locationManager.delegate = self
        
        guard CLLocationManager.locationServicesEnabled() else {
            //give permission
            return
        }
        locationManager.requestWhenInUseAuthorization()
        
    }
    private func initialSetup() {
       
        self.currentLocBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.currentLocBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.currentLocBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.currentLocBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.currentLocBtn.setTitleColor(UIColor.white, for: .focused)
        self.currentLocBtn.setTitleColor(UIColor.white, for: .highlighted)
        
        self.homeAddBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.homeAddBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.homeAddBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.homeAddBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.homeAddBtn.setTitleColor(UIColor.white, for: .focused)
        self.homeAddBtn.setTitleColor(UIColor.white, for: .highlighted)
    }
    
    
    @IBAction func chooseCurrentLoc(_ sender: Any) {
        setupLocationServices()
        
        
    }
    
    @IBAction func chooseAddress(_ sender: Any) {
        
        
    }
    

}

//Mark: - Location manager Delegate method
extension locationPopUpVC: CLLocationManagerDelegate {
    func locationManagerDidChangeAuthorization(_ manager: CLLocationManager) {
        
        switch CLLocationManager.authorizationStatus() {
            
        case.authorizedWhenInUse:
            //sent data to programme collection viewCell
            break
        case.denied:
        locationManager.requestWhenInUseAuthorization()
        case.notDetermined:
        locationManager.requestWhenInUseAuthorization()
            
        case.restricted:
            break
            
        case.authorizedAlways:
            break
            
        default:
            print("unknown")
      
        }
    }
    
    func locationManager(_ manager: CLLocationManager, didUpdateLocations locations: [CLLocation]) {
        print(locations)
    }
}
