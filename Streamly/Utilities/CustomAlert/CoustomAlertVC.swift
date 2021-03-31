//
//  CoustomAlertVC.swift
//  Streamly
//
//  Created by Rohit Singh on 12/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class CoustomAlertVC: UIViewController {

    @IBOutlet weak var headerLBL:UILabel!
    @IBOutlet weak var deatilBL:UILabel!

    var str:String = ""
    weak var delegate:PressContinue?
    var index:IndexPath?

    override func viewDidLoad() {
        super.viewDidLoad()
        self.initialSetup()

        // Do any additional setup after loading the view.
    }

    override func viewWillLayoutSubviews() {
           self.view.backgroundColor = UIColor(patternImage: UIImage(named: "bacgroundImage")!)
       }

    private func initialSetup()
    {
        headerLBL.text = appName
        deatilBL.text = str

    }


    @IBAction func back(_ sender:UIButton)
    {

        self.dismiss(animated: true, completion: {
            self.self.delegate?.goBack(self.index!)
        })

    }

}
