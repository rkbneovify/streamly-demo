//
//  ProgramCollectionViewCell.swift
//  Streamly
//
//  Created by Rohit Singh on 5/19/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class ProgramCollectionViewCell: UICollectionViewCell {
    
    @IBOutlet weak var timeLabel: UILabel!
    @IBOutlet weak var gradientView: GradientView!
    @IBOutlet weak var titleLabel: UILabel!

      override func awakeFromNib() {
        super.awakeFromNib()
        self.setup()
        gradientView.layer.cornerRadius = 8
        gradientView.clipsToBounds = true
        // Initialization code
      }

      required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
      //  self.setup()
      }

      override init(frame: CGRect) {
        super.init(frame: frame)
        self.setup()
      }

      func setup()
      {
        gradientView.borderWidth = 1.0
        gradientView.firstColor = UIColor.clear
        gradientView.secondColor = UIColor.clear
       // self.backgroundColor = UIColor.clear
      //  self.layer.borderWidth = 0.5
     //   self.layer.borderColor = UIColor.clear.cgColor
      }

  
    func configureWithModel(program: ProgramArray,row : Int)
    {
        self.titleLabel.text = program.title ?? ""
        self.timeLabel.text = "\(Utility.getDateFromTimeStampByFormat(from: program.start ?? 0.0) ?? "") - \(Utility.getDateFromTimeStampByFormat(from: program.end ?? 0.0) ?? "")"

    }

    // MARK: UIFocusEnvironment

    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        /*
         Update the label's alpha value using the `UIFocusAnimationCoordinator`.
         This will ensure all animations run alongside each other when the focus
         changes.
         */
        coordinator.addCoordinatedAnimations({
            if !self.isFocused {
                self.setup()
            }
            else {

                self.gradientView.borderWidth = 0.0
                self.gradientView.firstColor = UIColor.init(red: 0/255, green: 4/255, blue: 10/255, alpha: 0.7)
                self.gradientView.secondColor = UIColor.init(red: 0/255, green: 78/255, blue: 146/255, alpha: 1)


            }
        }, completion: nil)
    }
}

