//
//  PosterCell.swift
//  Streamly
//
//  Created by Rohit Singh on 3/16/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit
import TVUIKit
import SDWebImage

class PosterCell: UICollectionViewCell {
    
    // MARK: Outlets
    @IBOutlet weak var imageView: UIImageView!
    @IBOutlet weak var labelTxt: UILabel!
    @IBOutlet weak var backView: UIView!
    
    // MARK: Properties
    static let identiFier = "PosterCell"
    
    // MARK: LifeCycle
    override func awakeFromNib() {
        super.awakeFromNib()
       // self.backView.backgroundColor = UIColor.lightGray.withAlphaComponent(0.5)
        self.selectedUnSelectedCell(0)
        
        
        
    }
    
    // MARK: UICollectionReusableView
    override func prepareForReuse() {
        super.prepareForReuse()
        self.selectedUnSelectedCell(0)
    }
    
    // MARK: Helpers
    func confiGureData(_ data:ChannelArray, _ index:Int)
    {
        self.labelTxt.text = "\(data.id ?? index+1)"
        self.imageView.sd_setImage(with: URL(string: data.logo ?? ""), placeholderImage: UIImage(named: "Logo2"))
        
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
                self.selectedUnSelectedCell(0)
            }
            else {
                self.selectedUnSelectedCell(2)
                
            }
        }, completion: nil)
    }
    
    private func selectedUnSelectedCell(_ radius:Int)
    {
        self.backView.borderWidth = Double(radius)
    }
    
    
}
