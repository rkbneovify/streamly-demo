//
//  ChannelCollectionViewCell.swift
//  Streamly
//
//  Created by Rohit Singh on 5/19/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class ChannelCollectionViewCell: UICollectionViewCell {
    
   @IBOutlet weak var imageView: UIImageView!

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
        self.backgroundColor = UIColor.white
        self.layer.borderWidth = 0.5
        self.layer.borderColor = UIColor.gray.cgColor
      }

      func configureWith(channel: Channel) {
        let viewModel = ChannelViewModel(channel: channel)
        if let _ = viewModel.image {
       //   self.imageView.image = viewModel.image
        }
      }
    }
