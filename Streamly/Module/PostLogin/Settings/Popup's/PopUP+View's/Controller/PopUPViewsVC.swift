//
//  PopUPViewsVC.swift
//  Streamly
//
//  Created by Rohit Singh on 6/8/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class PopUPViewsVC: UIViewController {

    // MARK: - Outlets
    @IBOutlet weak var backView:UIView!
    @IBOutlet weak var backViewHeight: NSLayoutConstraint!

    // MARK: - propertis
    var item:Int?

    // MARK: - LifeCyle
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        self.initialViewSetup()
    }

    override func viewWillLayoutSubviews() {
        super.viewWillLayoutSubviews()

    }

    // MARK: - Helpers

    private func initialViewSetup()
    {
        switch item {
        case CustomPopUp.support.rawValue:
            self.supportViewSetup()
        case CustomPopUp.policies.rawValue:
            self.policiesViewSetup()
        case CustomPopUp.logOut.rawValue:
            self.logOUTViewSetup()
        default:
            return

        }
    }

    private func logOUTViewSetup()
    {
        self.backViewHeight.constant = 488
        let customView = LogOUTView()
        customView.frame = backView.bounds
        customView.backgroundColor = UIColor.black.withAlphaComponent(0.5)
        customView.cornerRadius = 10.0
        customView.delegate = self
        backView.addSubview(customView)
    }

    private func supportViewSetup()
    {

        self.backViewHeight.constant = 574
        let customView = SupportView()
        customView.frame = backView.bounds
        customView.backgroundColor = UIColor.black.withAlphaComponent(0.5)
        customView.cornerRadius = 10.0
        customView.delegate = self
        backView.addSubview(customView)
    }

    private func policiesViewSetup(){

        self.backViewHeight.constant = 488
        let customView = PrivacyPopUP()
        customView.frame = backView.bounds
        customView.backgroundColor = UIColor.black.withAlphaComponent(0.5)
        customView.cornerRadius = 10.0
        customView.delegate = self
        backView.addSubview(customView)
    }
}

extension PopUPViewsVC:CustomViews{

    func callAction(_ tag: Int) {
        if tag == 22
        {
            self.dismiss(animated: true, completion: nil)
        }
        else if tag == 33 {
            print("Logout done")
        }
    }


}

