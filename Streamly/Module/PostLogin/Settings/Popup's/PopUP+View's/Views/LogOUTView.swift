//
//  LogOUTView.swift
//  Streamly
//
//  Created by Rohit Singh on 6/8/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit


class LogOUTView: NibView {
    
    
    //MARK: Outlets
    @IBOutlet weak var noButton: UIButton!
    @IBOutlet weak var yesButton: UIButton!
    
    //MARK: properties
    weak var delegate:CustomViews?
  //  override var canBecomeFocused: Bool {
     //   return true
   // }
    
    //MARK: LifeCycle
    override func layoutSubviews() {
        super.layoutSubviews()
        self.noButton.setBtnProperties(self.noButton , false)
        self.yesButton.setBtnProperties(self.yesButton , false)
    }
    
    //MARK: Actions
    
    @IBAction func ButtonActions(_ sender: UIButton) {
        self.delegate?.callAction(sender.tag)
    }

    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if noButton.isFocused{
               coordinator.addCoordinatedAnimations({ () -> Void in
                self.noButton.layer.borderColor = UIColor.clear.cgColor
                self.yesButton.layer.borderColor = UIColor.white.cgColor
               }, completion: nil)

        } else if yesButton.isFocused{
               coordinator.addCoordinatedAnimations({ () -> Void in
              self.yesButton.layer.borderColor = UIColor.clear.cgColor
                self.noButton.layer.borderColor = UIColor.white.cgColor
               }, completion: nil)
           }

    }
}
