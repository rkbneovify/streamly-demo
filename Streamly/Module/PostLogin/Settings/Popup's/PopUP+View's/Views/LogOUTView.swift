//
//  LogOUTView.swift
//  Streamly
//
//  Created by Rohit Singh on 6/8/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit


class LogOUTView: NibView {


    //MARK:- Outlets
    @IBOutlet weak var noButton: UIButton!
    @IBOutlet weak var yesButton: UIButton!

    //MARK:- properties
    weak var delegate:CustomViews?

    //MARK:- LifeCycle
    override func layoutSubviews() {
        super.layoutSubviews()
        self.noButton.setBtnProperties(self.noButton)
        self.yesButton.setBtnProperties(self.yesButton)
    }

      //MARK:- Actions

    @IBAction func ButtonActions(_ sender: UIButton) {
        self.delegate?.callAction(sender.tag)
    }
}
