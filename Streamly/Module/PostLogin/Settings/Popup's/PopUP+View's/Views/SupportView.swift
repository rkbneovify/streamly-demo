//
//  SupportView.swift
//  Streamly
//
//  Created by Rohit Singh on 6/8/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class SupportView: NibView {

    //MARK:- Outlets
    
    @IBOutlet weak var okButton:UIButton!

    //MARK:- Properties

    weak var delegate:CustomViews?

    //MARK:- LifeCycle

    override func layoutSubviews() {
        super.layoutSubviews()
        self.okButton.setBtnProperties(self.okButton)
    }


    //MARK:- Actions

    @IBAction func ButtonActions(_ sender: UIButton) {
        self.delegate?.callAction(sender.tag)
    }
}
