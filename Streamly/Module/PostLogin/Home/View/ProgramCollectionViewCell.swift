//
//  ProgramCollectionViewCell.swift
//  Streamly
//
//  Created by Rohit Singh on 5/19/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class ProgramCollectionViewCell: UICollectionViewCell {
    
    @IBOutlet weak var gradientView: GradientView!
    @IBOutlet weak var titleLabel: UILabel!
    @IBOutlet weak var scheduleLabel: UILabel!
    @IBOutlet weak var durationLabel: UILabel!

      override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
      }

      required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        self.setup()
      }

      override init(frame: CGRect) {
        super.init(frame: frame)
        self.setup()
      }

      func setup() {
       // self.backgroundColor = UIColor.clear
      //  self.layer.borderWidth = 0.5
     //   self.layer.borderColor = UIColor.clear.cgColor
      }

    func configureWith(program: Program) {
    let viewModel = ProgramViewModel(program: program)
        
        self.titleLabel.text = viewModel.titleString
       // self.scheduleLabel.text = "\(viewModel.startTimeString) - \(viewModel.endTimeString)"
       // self.durationLabel.text = "\(program.duration)"

      //  self.scheduleLabel.textColor = UIColor.gray
     //   self.durationLabel.textColor = UIColor.gray
      }

    func configureWithModel(program: ProgramArray) {
    //   let viewModel = ProgramViewModel(program: program)
//        if program.startDate.timeIntervalSince1970 <= Date().timeIntervalSince1970
//        {
//            self.gradientView.firstColor = UIColor.firstGradientColor
//            self.gradientView.secondColor = UIColor.secondGradientColor
//        }
//        else {
//            self.gradientView.firstColor = UIColor.clear
//            self.gradientView.secondColor = UIColor.clear
//        }


        self.titleLabel.text = program.title ?? ""
        self.durationLabel.text = "\(program.startDate.string(format: "HH:mm"))"
          // self.scheduleLabel.text = "\(viewModel.startTimeString) - \(viewModel.endTimeString)"
          // self.durationLabel.text = "\(program.duration)"

         //  self.scheduleLabel.textColor = UIColor.gray
        //   self.durationLabel.textColor = UIColor.gray
         }
    }

