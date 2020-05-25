//
//  TimeCollectionViewCell.swift
//  Streamly
//
//  Created by Rohit Singh on 5/19/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class TimeCollectionViewCell: UICollectionViewCell {

    @IBOutlet weak var timeLabel: UILabel!

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
        self.layer.borderWidth = 0.5
        self.layer.borderColor = UIColor.clear.cgColor
      }
    }
