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
    
    @IBOutlet weak var okButton  :    UIButton!
    @IBOutlet weak var firstLbl  :    UILabel!
    @IBOutlet weak var secondLbl :    UILabel!
    @IBOutlet weak var msgLbl    :    UILabel!

    //MARK:- Properties

    weak var delegate:CustomViews?
    var contactInfo:ContactResponseData? {
        didSet {
            self.msgLbl.text = contactInfo?.message ?? "N/A"
            self.firstLbl.text = contactInfo?.data?[0]
            self.secondLbl.text = contactInfo?.data?[1]
        }
    }

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
