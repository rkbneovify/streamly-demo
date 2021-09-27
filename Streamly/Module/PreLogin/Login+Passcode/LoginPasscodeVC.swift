//
//  LoginPasscodeVC.swift
//  Streamly
//
//  Created by Rohit Singh on 8/9/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

class LoginPasscodeVC: UIViewController {

    //MARK: Outlets
    @IBOutlet weak var passCodeField:     UITextField!
    @IBOutlet weak var signBtn:           UIButton!

    //MARK: LifeCycle
    override func viewDidLoad() {
        super.viewDidLoad()

        self.initialSetup()

        // Do any additional setup after loading the view.
    }

    //MARK: Actions
    @IBAction func signIN(_ button:UIButton) {
        
    }
}

extension LoginPasscodeVC {

    private func initialSetup() {

        self.signBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.signBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.signBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.signBtn.setTitleColor(UIColor.white, for: .focused)
        self.signBtn.setTitleColor(UIColor.white, for: .highlighted)
        
    }

    

}

extension LoginPasscodeVC {
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if passCodeField.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in

                self.passCodeField.layer.borderColor = UIColor.clear.cgColor
            }, completion: nil)

        }       else {
            coordinator.addCoordinatedAnimations({ () -> Void in

                self.passCodeField.layer.borderColor = UIColor.firstGradientColor.cgColor
            }, completion: nil)
        }

    }
}


