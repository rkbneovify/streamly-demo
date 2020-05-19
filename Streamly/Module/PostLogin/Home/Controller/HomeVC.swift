//
//  HomeVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/15/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class HomeVC: UIViewController {

    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
    }


    @IBAction func logOUT(_ sender:UIButton)
    {
        Utility.logOut()
    }
}
