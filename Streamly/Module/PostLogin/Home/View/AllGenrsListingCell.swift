//
//  AllGenrsListingCell.swift
//  Streamly
//
//  Created by Rohit Singh on 3/4/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

class AllGenrsListingCell: UICollectionViewCell {

     // MARK: IBOutlet

    @IBOutlet weak var textLbl:UILabel!
    @IBOutlet weak var gradientView: UIView!

    // MARK: Properties

    static let identifier = "allGenrsListingCell"

    // MARK: Helper
    func confiGureData(_ data:String, _ selectedCategories:Bool)
    {
        self.textLbl.text = data.uppercased()
        
        if selectedCategories
        {
            self.gradientView.backgroundColor = UIColor.firstGradientColor
        }
        else
        {
            self.gradientView.backgroundColor = UIColor.clear

        }
    }

    private func setupUI()
    {
        self.gradientView.layer.borderWidth = 3.0
        self.gradientView.layer.borderColor = UIColor.secondGradientColor.cgColor
    }

    private func unFousedUI(){
        self.gradientView.layer.borderWidth = 0.0
    }



    // MARK: UIFocusEnvironment

      override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
          /*
           Update the label's alpha value using the `UIFocusAnimationCoordinator`.
           This will ensure all animations run alongside each other when the focus
           changes.
           */
          coordinator.addCoordinatedAnimations({
            if self.isFocused {
                self.setupUI()
            }
            else {
                self.unFousedUI()

            }
          }, completion: nil)
    }

    
    
}
