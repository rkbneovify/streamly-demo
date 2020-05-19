//
//  EPGCollectionViewCell.swift
//  Streamly
//
//  Created by Rohit Singh on 5/19/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class EPGCollectionViewCell: UICollectionViewCell {
    
   @IBOutlet weak var titleLabel: UILabel!

      override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
      }

      func setup() {
        self.titleLabel.text = "1"
        self.backgroundColor = UIColor.orange
      }
    }

