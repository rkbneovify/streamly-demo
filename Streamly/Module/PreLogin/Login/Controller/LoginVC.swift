//
//  LoginVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/14/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class LoginVC: UIViewController {

    //MARK:- Outlets
    @IBOutlet weak var userIDField:     UITextField!
    @IBOutlet weak var passwordField:   UITextField!
    @IBOutlet weak var rembemberMe:     UIButton!

    //MARK:- Properties

    //MARK:- Lifecycle Methods
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        self.initialSetup()
        
    }

    //MARK:- Helpers
    func initialSetup()
    {
        //self.showSpinner(onView: self.view)
    }

    //MARK:- Action
    @IBAction func btnActions( _ sender:UIButton)
    {
        // tag 2 for rembember me
        // tag 3 for Need Help
        // tag 4 for Sign IN
        // tag 5 for Register Now
        if sender.tag == 2 {

        }
        else if sender.tag == 3
        {

        }
        else if sender.tag == 4
        {
            guard self.validateEmail(text: userIDField.text ?? "") == "" else {
                return self.showSimpleAlertWithMessage(self.validateEmail(text: userIDField.text ?? ""))
            }
            guard self.validatePassword(text: passwordField.text ?? "") == "" else {
                return self.showSimpleAlertWithMessage(self.validatePassword(text: passwordField.text ?? ""))
            }

        }
        else if sender.tag == 5
        {

        }
    }
}


