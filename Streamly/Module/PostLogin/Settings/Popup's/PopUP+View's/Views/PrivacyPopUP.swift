//
//  PrivacyPopUP.swift
//  Streamly
//
//  Created by Rohit Singh on 6/9/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class PrivacyPopUP: NibView {

    //MARK: Outlets

    @IBOutlet weak var okButton:UIButton!
    @IBOutlet weak var privacyBtn:UIButton!
    @IBOutlet weak var policiesBtn:UIButton!

    //MARK: Properties

    weak var delegate:CustomViews?

    override var preferredFocusedView: UIView? {
        get {
            return self.okButton
        }
    }

    //MARK: LifeCycle

    override func layoutSubviews() {
        super.layoutSubviews()
        self.okButton.setBtnProperties(self.okButton)
        
    }


    //MARK: Actions

    @IBAction func buttonActions(_ sender: UIButton) {
        self.delegate?.callAction(sender.tag)
    }
}


