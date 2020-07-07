//
//  ChannelCollectionViewCell.swift
//  Streamly
//
//  Created by Rohit Singh on 5/19/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
import SDWebImage

class ChannelCollectionViewCell: UICollectionViewCell {
    
   @IBOutlet weak var imageView: UIImageView!

    @IBOutlet weak var channelName: UILabel!
    @IBOutlet weak var channelNumber: UILabel!
    override func awakeFromNib() {
        super.awakeFromNib()
        // These properties are also exposed in Interface Builder.
//               imageView.adjustsImageWhenAncestorFocused = true
//               imageView.clipsToBounds = false
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
        self.backgroundColor = UIColor.channelBox
        self.layer.borderWidth = 0.5
        self.layer.borderColor = UIColor.gray.cgColor
        self.layer.cornerRadius = 8
        
    }

    func configureWith(channel: Channel) {
        let viewModel = ChannelViewModel(channel: channel)
        if let _ = viewModel.logo {
            self.imageView.sd_setImage(with: URL(string: viewModel.logo ?? ""), placeholderImage: UIImage(named: "Logo2"))
        }
        self.channelName.text = channel.name
        self.channelNumber.text = "\(channel.number)"
    }



    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        coordinator.addCoordinatedAnimations({
            if self.isFocused {
               self.backgroundColor = UIColor.gray
                  }
                  else {
               self.backgroundColor = UIColor.channelBox
                  }
                  }, completion: nil)
    }
    }
