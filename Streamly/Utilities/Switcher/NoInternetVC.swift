//
//  NoInternetVC.swift
//  Streamly
//
//  Created by Rohit Singh on 9/7/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class NoInternetVC: UIViewController {


    // MARK: LifeCycle

    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
    }
    
    override func viewWillLayoutSubviews() {
        self.view.backgroundColor = UIColor(patternImage: UIImage(named: "bacgroundImage")!)
    }

    // MARK: Action

    @IBAction func retry(_ sender:UIButton)
    {
        if Reachability.isConnectedToNetwork()
        {
            Switcher.updateRootVC()

        }
        else {
              self.showSimpleAlertWithMessage("Please Connect With Network!")
            
        }
        
    }

}
